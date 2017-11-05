<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_forum_c_admin_checker extends Controller
{
	public function _edit($forum_id, $title)
	{
		if ($forum = $this->model()->check_forum($forum_id, $title))
		{
			return $forum;
		}
	}

	public function delete($forum_id, $title)
	{
		$this->ajax();

		if ($this->model()->check_forum($forum_id, $title))
		{
			return [$forum_id, $title];
		}
	}

	public function _categories_edit($category_id, $name)
	{
		if ($category = $this->model()->check_category($category_id, $name))
		{
			return $category;
		}
	}

	public function _categories_delete($category_id, $name)
	{
		$this->ajax();

		if ($category = $this->model()->check_category($category_id, $name))
		{
			return $category;
		}
	}
}
