<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Class ilPCBlog
 *
 * Blog content object (see ILIAS DTD)
 *
 * @author Jörg Lützenkirchen <luetzenkirchen@leifos.com>
 */
class ilPCBlog extends ilPageContent
{
    /**
     * @var ilObjUser
     */
    protected $user;

    /**
    * Init page content component.
    */
    public function init()
    {
        global $DIC;

        $this->user = $DIC->user();
        $this->setType("blog");
    }

    /**
    * Set node
    */
    public function setNode($a_node)
    {
        parent::setNode($a_node);		// this is the PageContent node
        $this->blog_node = $a_node->first_child();		// this is the blog node
    }

    /**
    * Create blog node in xml.
    *
    * @param	object	$a_pg_obj		Page Object
    * @param	string	$a_hier_id		Hierarchical ID
    */
    public function create(&$a_pg_obj, $a_hier_id, $a_pc_id = "")
    {
        $this->node = $this->createPageContentNode();
        $a_pg_obj->insertContent($this, $a_hier_id, IL_INSERT_AFTER, $a_pc_id);
        $this->blog_node = $this->dom->create_element("Blog");
        $this->blog_node = $this->node->append_child($this->blog_node);
    }

    /**
     * Set blog settings
     *
     * @param int $a_blog_id
     * @param array $a_posting_ids
     */
    public function setData($a_blog_id, array $a_posting_ids = null)
    {
        $ilUser = $this->user;
        
        $this->blog_node->set_attribute("Id", $a_blog_id);
        $this->blog_node->set_attribute("User", $ilUser->getId());

        // remove all children first
        $children = $this->blog_node->child_nodes();
        if ($children) {
            foreach ($children as $child) {
                $this->blog_node->remove_child($child);
            }
        }

        if (sizeof($a_posting_ids)) {
            foreach ($a_posting_ids as $posting_id) {
                $post_node = $this->dom->create_element("BlogPosting");
                $post_node = $this->blog_node->append_child($post_node);
                $post_node->set_attribute("Id", $posting_id);
            }
        }
    }

    /**
     * Get blog mode
     *
     * @return string
     */
    public function getBlogId()
    {
        if (is_object($this->blog_node)) {
            return $this->blog_node->get_attribute("Id");
        }
    }

    /**
    * Get blog postings
    *
    * @return array
    */
    public function getPostings()
    {
        $res = array();
        if (is_object($this->blog_node)) {
            $children = $this->blog_node->child_nodes();
            if ($children) {
                foreach ($children as $child) {
                    $res[] = $child->get_attribute("Id");
                }
            }
        }
        return $res;
    }
}
