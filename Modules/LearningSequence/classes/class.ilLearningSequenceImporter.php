<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

class ilLearningSequenceImporter extends ilXmlImporter
{
    protected ilObjUser $user;
    protected ilRbacAdmin $rbac_admin;
    protected ilLogger $log;
    protected ilObject $obj;
    protected array $data;

    public function init()
    {
        global $DIC;
        $this->user = $DIC["ilUser"];
        $this->rbac_admin = $DIC["rbacadmin"];
        $this->log = $DIC["ilLoggerFactory"]->getRootLogger();
    }

    public function importXmlRepresentation($entity, $id, $xml, $mapping)
    {
        if ($new_id = $mapping->getMapping("Services/Container", "objs", $id)) {
            $this->obj = ilObjectFactory::getInstanceByObjId($new_id, false);
        } else {
            $this->obj = new ilObjLearningSequence();
            $this->obj->create();
        }

        $parser = new ilLearningSequenceXMLParser($this->obj, $xml);
        $this->data = $parser->start();

        $mapping->addMapping("Modules/LearningSequence", "lso", $id, $this->obj->getId());
    }

    public function finalProcessing($mapping)
    {
        $this->buildSettings($this->data["settings"]);

        $this->obj->update();
    }

    public function afterContainerImportProcessing(ilImportMapping $mapping)
    {
        $this->updateRefId($mapping);
        $this->buildLSItems($this->data["item_data"], $mapping);
        $this->buildLPSettings($this->data["lp_settings"], $mapping);

        $roles = $this->obj->getLSRoles();
        $roles->addLSMember(
            $this->user->getId(),
            $roles->getDefaultAdminRole()
        );
    }

    protected function updateRefId(ilImportMapping $mapping) : void
    {
        $old_ref_id = $this->data["object"]["ref_id"];
        $new_ref_id = $mapping->getMapping("Services/Container", "refs", $old_ref_id);

        $this->obj->setRefId((int) $new_ref_id);
    }

    protected function buildLSItems(array $ls_data, ilImportMapping $mapping) : void
    {
        $ls_items = array();
        foreach ($ls_data as $data) {
            $old_ref_id = $data["id"];
            $new_ref_id = $mapping->getMapping("Services/Container", "refs", $old_ref_id);

            $post_condition = new ilLSPostCondition(
                (int) $new_ref_id,
                $data["ls_item_pc_condition_type"],
                $data["ls_item_pc_value"]
            );

            $ls_items[] = new LSItem(
                $data["ls_item_type"] ?? "",
                $data["ls_item_title"] ?? "",
                $data["ls_item_description"] ?? "",
                $data["ls_item_icon_path"] ?? "",
                (bool) $data["ls_item_is_online"],
                (int) $data["ls_item_order_number"],
                $post_condition,
                (int) $new_ref_id
            );
        }

        $this->obj->storeLSItems($ls_items);
    }

    protected function buildSettings(array $ls_settings) : void
    {
        $settings = $this->obj->getLSSettings();
        $settings = $settings
            ->withAbstract($ls_settings["abstract"])
            ->withExtro($ls_settings["extro"])
            ->withMembersGallery((bool) $ls_settings["members_gallery"])
        ;

        if ($ls_settings["abstract_img"] != "") {
            $path = $this->getNewImagePath(ilLearningSequenceFilesystem::IMG_ABSTRACT, $ls_settings['abstract_img']);
            $abstract_img_data = $this->decodeImageData($ls_settings["abstract_img_data"]);
            $this->writeToFileSystem($abstract_img_data, $path);
            $settings = $settings
                ->withAbstractImage($path)
            ;
        }

        if ($ls_settings["extro_img"] != "") {
            $path = $this->getNewImagePath(ilLearningSequenceFilesystem::IMG_EXTRO, $ls_settings['extro_img']);
            $extro_img_data = $this->decodeImageData($ls_settings["extro_img_data"]);
            $this->writeToFileSystem($extro_img_data, $path);
            $settings = $settings
                ->withExtroImage($path)
            ;
        }

        $this->obj->updateSettings($settings);
    }

    protected function buildLPSettings(array $lp_settings, ilImportMapping $mapping) : void
    {
        $collection = ilLPCollection::getInstanceByMode($this->obj->getId(), (int) $lp_settings["lp_mode"]);

        $new_ref_ids = array_map(function ($old_ref_id) use ($mapping) {
            return $mapping->getMapping("Services/Container", "refs", $old_ref_id);
        }, $lp_settings["lp_item_ref_ids"]);

        if (!is_null($collection)) {
            $collection->activateEntries($new_ref_ids);
        }

        $settings = new ilLPObjSettings($this->obj->getId());
        $settings->setMode((int) $lp_settings["lp_mode"]);
        $settings->insert();
    }

    protected function decodeImageData(string $data) : string
    {
        return base64_decode($data);
    }

    protected function getNewImagePath(string $type, string $path) : string
    {
        $fs = $this->obj->getDI()['db.filesystem'];
        return $fs->getStoragePathFor(
            $type,
            $this->obj->getId(),
            $fs->getSuffix($path)
        );
    }

    protected function writeToFileSystem($data, string $path) : void
    {
        file_put_contents($path, $data);
    }
}
