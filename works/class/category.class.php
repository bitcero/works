<?php
// $Id: pwcategory.class.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class Works_Category extends RMObject
{
    public function __construct($id = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_works_categories');
        $this->setNew();
        $this->initVarsFromTable();
        if ('' == $id) {
            return;
        }

        if (is_numeric($id)) {
            if ($this->loadValues($id)) {
                $this->unsetNew();
            }

            return true;
        }

        $this->primary = 'nameid';

        $parts = explode('/', $id);

        if (count($parts) > 1) {
            $sql = 'SELECT C' . (count($parts)) . ".* FROM $this->_dbtable as C1\n";
            for ($i = 2; $i <= count($parts); $i++) {
                $sql .= " INNER JOIN $this->_dbtable AS C" . ($i) . ' ON C' . ($i) . '.parent=C' . ($i - 1) . ".id_cat\n";
            }
            $sql .= " WHERE C1.nameid = '$parts[0]' AND C1.parent = 0\n";

            $row = $this->db->fetchArray($this->db->query($sql));
            $this->setVars($row);
            $this->unsetNew();
            $this->primary = 'id_cat';

            return true;
        }

        if ($this->loadValues($id)) {
            $this->unsetNew();
        }
        $this->primary = 'id_cat';
    }

    public function id()
    {
        return $this->getVar('id_cat');
    }

    /**
     * Get the category link formated
     */
    public function permalink()
    {
        $mc = RMSettings::module_settings('works');

        $link = XOOPS_URL . '/';

        if (0 == $this->parent) {
            if ($mc->permalinks) {
                $link .= trim($mc->htbase, '/') . '/category/' . $this->nameid . '/';
            } else {
                $link .= 'modules/works/index.php?p=category&amp;id=' . $this->nameid;
            }

            return $link;
        }

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'SELECT T2.nameid
                FROM (
                    SELECT
                        @r AS _id,
                        (SELECT @r := parent FROM ' . $db->prefix('mod_works_categories') . ' WHERE id_cat = _id) AS parent,
                        @l := @l + 1 AS lvl
                    FROM
                        (SELECT @r := ' . $this->id() . ', @l := 0) vars,
                        ' . $db->prefix('mod_works_categories') . ' m
                    WHERE @r <> 0) T1
                JOIN ' . $db->prefix('mod_works_categories') . ' T2
                ON T1._id = T2.id_cat
                ORDER BY T1.lvl DESC;';

        $result = $db->query($sql);

        $link .= 'category/';
        while (false !== (list($id) = $db->fetchRow($result))) {
            $link .= $id . '/';
        }

        $link .= $this->id() . '/';

        return $link;
    }

    /**
     * @desc Obtiene el total de trabajos de la categoría
     **/
    public function works()
    {
        $tw = $this->db->prefix('mod_works_works');
        $tr = $this->db->prefix('mod_works_categories_rel');

        $sql = "SELECT COUNT(*) FROM $tw as w, $tr as r WHERE r.category='" . $this->id() . "' AND w.id_work=r.work";
        list($num) = $this->db->fetchRow($this->db->query($sql));

        return $num;
    }

    public function save()
    {
        if ($this->isNew()) {
            return $this->saveToTable();
        }

        return $this->updateTable();
    }

    public function delete()
    {
        return $this->deleteFromTable();
    }
}
