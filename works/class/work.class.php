<?php

/**
 * Professional Works
 *
 * Copyright © 2015 Eduardo Cortés http://www.redmexico.com.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.redmexico.com.mx)
 * @license      GNU GPL 2
 * @package      works
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.redmexico.com.mx
 * @url          http://www.eduardocortes.mx
 */
class Works_Work extends RMObject
{
    /**
     * Custom fields storage
     */
    private $meta = [];
    /**
     * @var array Images container
     */
    private $images = [];
    /**
     * @var array Videos container
     */
    private $videos = [];
    /**
     * @var array Categories container
     */
    private $categories = [];

    public function __construct($id = null)
    {
        // Prevent to be translated
        $this->noTranslate = [
            'titleid',
            'customer',
            'web',
            'url',
            'image',
            'status',
            'groups',
        ];

        $this->db       = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_works_works');
        $this->setNew();
        $this->initVarsFromTable();

        $this->setVarType('groups', XOBJ_DTYPE_ARRAY);

        $this->ownerName = 'works';
        $this->ownerType = 'module';

        if (null === $id) {
            return;
        }

        if (is_numeric($id)) {
            if (!$this->loadValues((int)$id)) {
                return;
            }
        } else {
            $this->primary = 'titleid';
            if (!$this->loadValues($id)) {
                $this->primary = 'id_work';

                return;
            }
        }

        $this->primary = 'id_work';
        $this->unsetNew();
    }

    public function id()
    {
        return $this->getVar('id_work');
    }

    /**
     * Get categories for this work.
     * @param string $type <p>Can be 'id', 'name', 'objects' or 'all'. 'id' returns array with ids from categories. 'name' returns an array with [id] => [name] pairs.
     *                     'all' returns array with all data from categories, such as is stored in database table.</p>
     * @return array
     */
    public function categories($type = 'id')
    {
        if (empty($this->categories)) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('mod_works_categories_rel') . ' as r LEFT JOIN ' . $this->db->prefix('mod_works_categories') . ' as c ON
                c.id_cat = r.category WHERE r.work = ' . $this->id();

            $result = $this->db->query($sql);
            while (false !== ($row = $this->db->fetchArray($result))) {
                $this->categories[$row['id_cat']] = $row;
            }
        }

        if ('id' == $type) {
            $ret = [];
            foreach ($this->categories as $cat) {
                $ret[] = $cat['id_cat'];
            }

            return $ret;
        }

        if ('name' == $type) {
            $ret = [];
            foreach ($this->categories as $id => $cat) {
                $ret[$id] = $cat['name'];
            }

            return $ret;
        }

        if ('objects' == $type) {
            $ret = [];
            foreach ($this->categories as $id => $cat) {
                $tmp = new Works_Category();
                $tmp->assignVars($cat);
                $ret[] = $tmp;
            }

            return $ret;
        }

        return $this->categories;
    }

    /**
     * @desc Incrementar el número de visitas
     */
    public function addView()
    {
        $sql = 'UPDATE ' . $this->db->prefix('mod_works_works') . " SET views=views+1 WHERE id_work='" . $this->id() . "'";
        if (!$this->db->queryF($sql)) {
            $this->addError($this->db->error());

            return false;
        }

        return true;
    }

    public function permalink()
    {
        $mc = RMSettings::module_settings('works');

        $link = XOOPS_URL . '/';
        if ($mc->permalinks) {
            $link .= trim($mc->htbase, '/') . '/' . $this->titleid . '/';
        } else {
            $link .= 'modules/works/index.php?p=work&amp;id=' . $this->id();
        }

        return $link;
    }

    /**
     * Set multiple images for current work
     * @param array $images <p>Pair/value array containing all specified images for work.
     *                      All these images must be taked from RMCommon Images Manager.</p>
     */
    public function set_images($images)
    {
        $this->images = [];

        foreach ($images as $image) {
            $temp           = explode('|', $image, 2);
            $this->images[] = [
                'title' => $temp[1],
                'url'   => $temp[0],
            ];
        }
    }

    /**
     * Load work images
     * @return array
     */
    public function images()
    {
        if (empty($this->images)) {
            $this->images = Works_Functions::images($this->id());
        }

        return $this->images;
    }

    public function videos()
    {
        if (empty($this->videos)) {
            $this->videos = Works_Functions::videos($this->id());
        }

        return $this->videos;
    }

    /**
     * Set custom data for work
     * @param array $names  All meta names
     * @param array $values All meta values
     */
    public function set_meta($names, $values)
    {
        $this->meta = [];

        foreach ($names as $id => $name) {
            $this->meta[$name] = $values[$id];
        }
    }

    public function get_meta($name = '')
    {
        if (empty($this->meta)) {
            $this->meta = Works_Functions::metas($this->id());
        }

        if ('' != $name && isset($this->meta[$name])) {
            return $this->meta[$name];
        }

        return $this->meta;
    }

    public function save()
    {
        if ($this->isNew()) {
            $return = $this->saveToTable();
        } else {
            $return = $this->updateTable();
        }

        if (!$return) {
            return false;
        }

        // Save images

        $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_works_images') . ' WHERE work = ' . $this->id());
        $sql = '';
        foreach ($this->images as $image) {
            $sql .= "('$image[title]','$image[url]'," . $this->id() . '),';
        }

        if ('' != $sql) {
            $sql    = $sql = 'INSERT INTO ' . $this->db->prefix('mod_works_images') . ' (title,image,work) VALUES ' . rtrim($sql, ',');
            $return = $this->db->queryF($sql);

            if (!$return) {
                $this->addError(__('Images could not be saved:', 'works') . ' ' . $this->db->error());
            }
        }

        // Save meta
        $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_works_meta') . ' WHERE work = ' . $this->id());
        $sql  = '';
        $myts = MyTextSanitizer::getInstance();
        foreach ($this->meta as $name => $value) {
            $sql .= "('" . $myts->addSlashes($name) . "','" . $myts->addSlashes($value) . "'," . $this->id() . '),';
        }

        if ('' != $sql) {
            $sql    = 'INSERT INTO ' . $this->db->prefix('mod_works_meta') . ' (`name`,`value`,`work`) VALUES ' . rtrim($sql, ',');
            $return = $this->db->queryF($sql);

            if (!$return) {
                $this->addError(__('Custom data could not be saved:', 'works') . ' ' . $this->db->error());
            }
        }

        return $return;
    }

    public function delete()
    {
        $return = $this->deleteFromTable();

        if (!$return) {
            return false;
        }

        $return = $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_works_images') . ' WHERE work = ' . $this->id());
        if (!$return) {
            $this->addError(__('Images data could not be deleted:', 'works') . ' ' . $this->db->error());
        }

        // Delete videos
        $videos = Works_Functions::videos($this->id());

        foreach ($videos as $video) {
            @unlink(str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $video['image']));
        }

        $return = $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_works_videos') . ' WHERE work = ' . $this->id());
        if (!$return) {
            $this->addError(__('Videos data could not be deleted:', 'works') . ' ' . $this->db->error());
        }

        $return = $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_works_meta') . ' WHERE work = ' . $this->id());
        if (!$return) {
            $this->addError(__('Custom data could not be deleted:', 'works') . ' ' . $this->db->error());
        }

        $return = $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_works_categories_rel') . ' WHERE work = ' . $this->id());
        if (!$return) {
            $this->addError(__('Categories relations could not be deleted:', 'works') . ' ' . $this->db->error());
        }

        return $return;
    }
}
