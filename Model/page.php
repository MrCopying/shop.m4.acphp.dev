<?php

namespace Model;
use Library\Model;

class Page extends Model
{
    public function getList($only_published = false)
    {
        $sql = 'SELECT p.id, p.alias, p.alias_id as uri, p.name, p.content, p.is_published FROM page as p WHERE p.alias IS NOT NULL';
        $union = ' UNION SELECT p.id, p.alias, a.uri, p.name, p.content, p.is_published FROM page as p JOIN alias as a ON p.alias_id = a.id WHERE 1';
        if ($only_published) {
            $sql.= ' AND is_published = 1';
            $union.= ' AND is_published = 1';
        }
        return $this->db->query($sql . $union);
    }

    public function getByAliasId($alias)
    {
        $alias = (int)$alias;
        $sql = "select * from page where alias_id = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }
    public function getByAlias($alias)
    {
        $alias = $this->db->escape($alias);
        $sql = "select * from page where alias = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }
    public function getById($id)
    {
        $id = (int)$id;
        $sql = "select * from page where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }
    public function save($data, $id)
    {
        if ( !isset($data['alias']) || !isset($data['title']) || !isset($data['content']) ) {
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['name']);
        $content = $this->db->escape($data['content']);
        $is_published = isset($data['is_published']) ? 1 : 0;

        if (!$id) { //Add new record
            $sql = "
              insert into `page`
                      set `alias` = '{$alias}',
                          `title` = '{$title}',
                          `content` = '{$content}',
                          `is_published` = '{$is_published}'
            ";
        } else { //Update existing record
            $sql = "
              update `page`
                      set `alias` = '{$alias}',
                          `title` = '{$title}',
                          `content` = '{$content}',
                          `is_published` = '{$is_published}'
                      where id = {$id}
            ";
        }

        return $this->db->query($sql);
    }
    public function delete($id)
    {
        $id = (int)$id;
        $sql = "delete from page where id = {$id}";
        return $this->db->query($sql);
    }

}