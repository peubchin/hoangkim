<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * Importion: This is change page url admin
 */
if (!function_exists('get_admin_url')) {

    function get_admin_url($module_slug = '') {
        $html = '';
        $ci = & get_instance();
        $base_url = $ci->config->item('base_url');
        $html .= $base_url . 'admin';
        if (trim($module_slug) != '') {
            $html .= '/' . $module_slug;
        }

        return $html;
    }

}

if (!function_exists('display_menu_table')) {
	function display_menu_table($parentId, $ctgLists, $ctgData, $depth) {
		$ci = & get_instance();
		$char = '&brvbar;&rarr;';
		$html = '';

		if (isset($ctgLists[$parentId])) {
			foreach ($ctgLists[$parentId] as $childId) {
				if ($parentId == 0) {
					$tab = '';
				} else if (isset($ctgLists[$childId])) {
					$tab = str_repeat($char, $depth);
				} else {
					$tab = str_repeat($char, $depth);
				}

				$html .= '<tr>
							<td class="text-center">
								<input style="width: 80px;" class="text-right form-control" name="order[]" type="text" value="' . $ctgData[$childId]['order'] . '">
								<input class="text-right form-control" name="ids[]" type="hidden" value="' . $ctgData[$childId]['lurl'] . '">
							</td>
							<td>
								<strong>' . $tab . $ctgData[$childId]['lname'] . '</strong>
							</td>
							<td class="text-center">
								<span class="badge bg-green">' . display_value_array($ci->config->item('menu_modules'), $ctgData[$childId]['position']) . '</span>
							</td>
							<td class="text-center">
								<span class="label label-info">' . $ctgData[$childId]['menu_type_name'] . '</span>
							</td>
							<td class="text-center">
								<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="' . get_admin_url('menu/content/' . $ctgData[$childId]['lurl']) . '" class="">Sửa</a>
								&nbsp;-&nbsp;
								<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a title="Xóa" href="' . get_admin_url('menu/delete/' . $ctgData[$childId]['lurl']) . '" class="delete_bootbox">Xóa</a>
							</td>
						</tr>';
				$html .= display_menu_table($childId, $ctgLists, $ctgData, $depth + 1);
			}
		}

		return $html;
	}
}

if (!function_exists('display_table_cat')) {
	function display_table_cat($parentId, $ctgLists, $ctgData, $depth) {
		$char = '&brvbar;&rarr;';
		$html = '';

		if (isset($ctgLists[$parentId])) {
			foreach ($ctgLists[$parentId] as $childId) {
				if ($parentId == 0) {
					$tab = '';
				} else if (isset($ctgLists[$childId])) {
					$tab = str_repeat($char, $depth);
				} else {
					$tab = str_repeat($char, $depth);
				}

				$checked = ($ctgData[$childId]['linhome'] == 1) ? ' checked="checked"' : '';
				$html .= '<tr>
							<td class="text-center">
								<input style="width: 80px;" class="text-right form-control" name="order[]" type="text" value="' . $ctgData[$childId]['order'] . '">
								<input class="text-right form-control" name="ids[]" type="hidden" value="' . $ctgData[$childId]['lid'] . '">
							</td>
							<td>
								<strong>' . $tab . $ctgData[$childId]['lname'] . '</strong>
							</td>
							<td class="text-center">
								<input type="checkbox" name="inhome[]" class="change-inhome flat-blue" value="' . $ctgData[$childId]['lid'] . '"' . $checked . ' />
							</td>
							<td class="text-center">
								<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="' . get_admin_url('shops/cat/content/' . $ctgData[$childId]['lid']) . '" class="">Sửa</a>
								&nbsp;-&nbsp;
								<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a title="Xóa" href="' . get_admin_url('shops/cat/delete/' . $ctgData[$childId]['lid']) . '" class="delete_bootbox">Xóa</a>
							</td>
						</tr>';
				$html .= display_table_cat($childId, $ctgLists, $ctgData, $depth + 1);
			}
		}

		return $html;
	}
}

if (!function_exists('display_table_cat_post')) {

    function display_table_cat_post($parentId, $ctgLists, $ctgData, $depth) {
        $char = '&brvbar;&rarr;';
        $html = '';

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists[$childId])) {
                    $tab = str_repeat($char, $depth);
                } else {
                    $tab = str_repeat($char, $depth);
                }

                $checked = ($ctgData[$childId]['linhome'] == 1) ? ' checked="checked"' : '';
                $html .= '<tr>
                        <td class="text-center">
                            <input style="width: 80px;" class="text-right form-control" name="order[]" type="text" value="' . $ctgData[$childId]['order'] . '">
                            <input class="text-right form-control" name="ids[]" type="hidden" value="' . $ctgData[$childId]['lid'] . '">
                        </td>
                        <td>
                            <strong>' . $tab . $ctgData[$childId]['lname'] . '</strong>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="inhome[]" class="change-inhome flat-blue" value="' . $ctgData[$childId]['lid'] . '"' . $checked . ' />
                        </td>
                        <td class="text-center">' . $ctgData[$childId]['edit_time'] . '</td>
                        <td class="text-center">
                            <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="' . get_admin_url('posts/cat/content/' . $ctgData[$childId]['lid']) . '" class="">Sửa</a>
                            &nbsp;-&nbsp;
                            <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a title="Xóa" href="' . get_admin_url('posts/cat/delete/' . $ctgData[$childId]['lid']) . '" class="delete_bootbox">Xóa</a>
                        </td>
                    </tr>';
                $html .= display_table_cat_post($childId, $ctgLists, $ctgData, $depth + 1);
            }
        }

        return $html;
    }

}

if (!function_exists('get_menutype')) {

    function get_menutype($menu_type, $option_selected = 0) {

        $html = '';
        foreach ($menu_type as $value) {
            $selected = '';
            if ($value["id"] == $option_selected) {
                $selected = ' selected="selected"';
            }
            $html .= "<option value='" . $value['id'] . "' $selected>" . $value["name"] . "</option>";
        }

        return $html;
    }

}

if (!function_exists('get_children')) {

    function get_children($parentId, $ctgLists, $ctgData) {
        $children = array();

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                $children[] = $childId;
                $children = array_merge($children, get_children($childId, $ctgLists, $ctgData));
            }
        }

        return $children;
    }

}

if (!function_exists('get_num_children')) {

    function get_num_children($parentId, $ctgLists, $ctgData) {
        $children = array();

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                $children[] = $childId;
                $children = array_merge($children, get_num_children($childId, $ctgLists, $ctgData));
            }
        }

        return $children;
    }

}

if (!function_exists('menu_left')) {

    function menu_left($parentId, $ctgLists, $ctgData) {
        $html = '';
        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                $html .= '<p class="mar-b-5 size-13-px"><a class="size-16-px a-text-datcam" href="' . $ctgData[$childId]['lurl'] . '">' . $ctgData [$childId]['lname'] . '</a></p>';
                if ($parentId != 0) {
                    $html .= menu_left($childId, $ctgLists, $ctgData);
                }
            }
        }

        return $html;
    }

}

if (!function_exists('menu_top')) {

    function menu_top($parentId, $ctgLists, $ctgData) {
        $html = '';       // stores and returns the html code with Menu lists
// if parent item with child IDs in ctgLists
        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $class_ul = '';
            } else {
                $class_ul = '';
            }
            if ($parentId != 0) {
                $html = "<ul$class_ul>";      // open UL
            }
// traverses the array with child IDs of current parent, and adds them in LI tags, with their data from $ctgData
            foreach ($ctgLists[$parentId] as $childId) {
// define CSS class in anchors, useful to be used in CSS style to design the menu
                if ($parentId == 0) {
                    if (isset($ctgLists[$childId])) {
                        $more = '';
                    } else {
                        $more = '';
                    }
                    $clsl = '';       // class for anchors in main /first categories
                    $clsa = '';       // class for anchors in main /first categories
                } else if (isset($ctgLists[$childId])) {
                    $more = '';
                    $clsl = '';       // class for anchors in main /first categories
                    $clsa = '';       // class for anchors in lists with childs
                } else {
//$clsl = ' class="dropdown"';       // class for anchors in main /first categories
                    $more = '';
                    $clsl = '';       // class for anchors in main /first categories
                    $clsa = '';
                }

// open LI
                $html .= '<a href="' . $ctgData[$childId]['lurl'] . '" title="' . $ctgData[$childId]['lname'] . '"' . $clsa . '>' . $ctgData[$childId]['lname'];

                $html .= menu_top($childId, $ctgLists, $ctgData);     // re-calls the function to find parent with child-items recursively

                $html .= '</a>';      // close LI
            }
            if ($parentId != 0) {
                $html .= '</ul>';       // close UL
            }
        }

        return $html;
    }

}


if (!function_exists('menu_main')) {

    function menu_main($parentId, $ctgLists, $ctgData, $depth = 0) {
        $html = '';
        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $depth++;
            }
            if ($depth != 2) {
                $class_ul = ' class="wsmenu-submenu"';
            } else {
                $class_ul = ' class="wsmenu-submenu-sub"';
            }
            if ($parentId != 0) {
                $html = "<ul$class_ul>";      // open UL
            }
            foreach ($ctgLists[$parentId] as $childId) {
                if (isset($ctgLists[$childId])) {
                    if ($parentId != 0 && $depth != 3) {
                        $depth++;
                    }
                    $clsl = '';
                    $amore = '<span class="wsmenu-click"><i class="wsmenu-arrow fa fa-angle-down"></i></span><span class="wsmenu-click"></span>';
                    $clsa = '';
                    if ($parentId == 0) {
                        $clsspan = '<span class="arrow"></span>';
                    } else {
                        $clsspan = '';
                    }
                } else {
                    $clsl = '';
                    $amore = '';
                    if ($parentId == 0) {
                        $clsa = '';
                    } else {
                        $clsa = '';
                    }
                    $clsspan = '';
                }

                if ($parentId != 0) {
                    $a_before = '<i class="fa fa-angle-right"></i>';
                } else {
                    $a_before = '';
                }

                $a_active = trim($clsa . (($ctgData[$childId]['lurl'] == current_url()) ? ' active' : ''));
                if ($a_active != '') {
                    $a_active = ' class="' . $a_active . '"';
                }

                $html .= '<li>' . '<a' . $a_active . ' href="' . $ctgData[$childId]['lurl'] . '">' . $a_before . $ctgData[$childId]['lname'] . $clsspan . '</a>';

                $html .= menu_main($childId, $ctgLists, $ctgData, $depth);

                $html .= '</li>';
            }
            if ($parentId != 0) {
                $html .= '</ul>';
            }
        }

        return $html;
    }

}


if (!function_exists('menu_mega')) {

    function menu_mega($parentId, $ctgLists, $ctgData, $dath) {
        $html = '';
        if (isset($ctgLists[$parentId])) {
            if ($parentId != 0 && $dath != 1) {
                $html .= "<ul>";
            } elseif ($parentId != 0 && $dath == 1) {
                $html .= '<div class="submenu">';
            }

            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $html .= '<li>
  <a href="' . $ctgData[$childId]['lurl'] . '">
  <img src="' . $ctgData[$childId]['limg'] . '" alt="">
  <h3>' . $ctgData[$childId]['lname'] . '</h3>
  <p>' . $ctgData[$childId]['ldescription'] . '</p>
  </a>';

                    $html .= menu_mega($childId, $ctgLists, $ctgData, $dath + 1);
                    $html .= '</li>';
                } else if ($dath == 1) {
                    $html .= '<div class="subsub">
  <h4>' . $ctgData[$childId]['lname'] . '</h4>';
                    if (!isset($ctgLists[$childId])) {
                        $html .= '</div>';
                    }
                    $html .= menu_mega($childId, $ctgLists, $ctgData, $dath + 1);
                } else {
                    $html .= '<li><a href="' . $ctgData[$childId]['lurl'] . '" title="' . $ctgData[$childId]['lname'] . '"' . '>' . $ctgData[$childId]['lname'] . '</a>';
                    $html .= menu_mega($childId, $ctgLists, $ctgData, $dath + 1);
                    $html .= '</li>';
                }
            }
            if ($parentId != 0 && $dath != 1) {
                $html .= '</ul></div>';
            } elseif ($parentId != 0 && $dath == 1) {
                $html .= '</div>';
            }
        }

        return $html;
    }

}

if (!function_exists('menu_mobile')) {

    function menu_mobile($parentId, $ctgLists, $ctgData) {
        $html = '';       // stores and returns the html code with Menu lists
        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $class_ul = ' class="level1"';
            } else {
                $class_ul = ' class=""';
            }
            if ($parentId != 0) {
                $html = "<ul $class_ul>";      // open UL
            }
// traverses the array with child IDs of current parent, and adds them in LI tags, with their data from $ctgData
            foreach ($ctgLists[$parentId] as $childId) {
// define CSS class in anchors, useful to be used in CSS style to design the menu
                if ($parentId == 0) {
                    if (isset($ctgLists[$childId])) {
                        $more = ' <i class="fa fa-caret-down"></i>';
                    } else {
                        $more = '';
                    }
                    $clsl = ' class="level0 parent drop-menu"';       // class for anchors in main /first categories
                    $clsa = '';       // class for anchors in main /first categories
                } else if (isset($ctgLists[$childId])) {
                    $more = '';
                    $clsl = ' class="level1 parent drop-menu"';       // class for anchors in main /first categories
                    $clsa = '';       // class for anchors in lists with childs
                } else {
//$clsl = ' class="dropdown"';       // class for anchors in main /first categories
                    $more = '';
                    $clsl = '';       // class for anchors in main /first categories
                    $clsa = '';
                }

// open LI
                $html .= '<li' . $clsl . '><a href="' . $ctgData[$childId]['lurl'] . '" title="' . $ctgData[$childId]['lname'] . '"' . $clsa . '><span>' . $ctgData[$childId]['lname'] . '</span>' . $more . '</a>';

                $html .= menu_mobile($childId, $ctgLists, $ctgData);     // re-calls the function to find parent with child-items recursively

                $html .= '</li>';      // close LI
            }
            if ($parentId != 0) {
                $html .= '</ul>';       // close UL
            }
        }

        return $html;
    }

}

if (!function_exists('menu_none')) {

    function menu_none($parentId, $ctgLists, $ctgData) {
        $html = '';       // stores and returns the html code with Menu lists
// if parent item with child IDs in ctgLists
        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $class_ul = ' class="nav navbar-nav navbar-right"';
            } else {
                $class_ul = ' class="links"';
            }
            if ($parentId != 0) {
                $html = "<ul $class_ul>";      // open UL
            }
// traverses the array with child IDs of current parent, and adds them in LI tags, with their data from $ctgData
            foreach ($ctgLists[$parentId] as $childId) {
// define CSS class in anchors, useful to be used in CSS style to design the menu
                if ($parentId == 0) {
                    if (isset($ctgLists[$childId])) {
                        $more = ' <i class="fa fa-caret-down"></i>';
                    } else {
                        $more = '';
                    }
                    $clsl = ' class="level0 parent drop-menu"';       // class for anchors in main /first categories
                    $clsa = '';       // class for anchors in main /first categories
                } else if (isset($ctgLists[$childId])) {
                    $more = '';
                    $clsl = ' class="first"';       // class for anchors in main /first categories
                    $clsa = '';       // class for anchors in lists with childs
                } else {
//$clsl = ' class="dropdown"';       // class for anchors in main /first categories
                    $more = '';
                    $clsl = 'last';       // class for anchors in main /first categories
                    $clsa = '';
                }

// open LI
                $html .= '<li><a href="' . $ctgData [$childId]['lurl'] . '" title="' . $ctgData [$childId]['lname'] . '"' . $clsa . '>' . $ctgData [$childId]['lname'] . '</a>';

                $html .= menu_none($childId, $ctgLists, $ctgData);     // re-calls the function to find parent with child-items recursively

                $html .= '</li>';      // close LI
            }
            if ($parentId != 0) {
                $html .= '</ul>';       // close UL
            }
        }

        return $html;
    }

}

if (!function_exists('menu_bottom')) {

    function menu_bottom($parentId, $ctgLists, $ctgData) {
        $html = '';

        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $class_ul = '';
            } else {
                $class_ul = ' class="cont"';
            }
            if ($parentId != 0) {
                $html .= "<ul>";
            }
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $html .= "<div class='topbot'>";
                    $html .= '<h5>' . $ctgData[$childId]['lname'] . '</h5>';

                    $html .= menu_bottom($childId, $ctgLists, $ctgData);
                } else {
                    $html .= '<li><a href="' . $ctgData[$childId]['lurl'] . '" title="' . $ctgData[$childId]['lname'] . '">' . $ctgData[$childId]['lname'] . '</a>';

                    $html .= menu_bottom($childId, $ctgLists, $ctgData);

                    $html .= '</li>';
                }
            }

            if ($parentId != 0) {
                $html .= '</ul></div>';       // close UL
            }
        }

        return $html;
    }

}

if (!function_exists('menu_single')) {

    function menu_single($parentId, $ctgLists, $ctgData) {
        $html = '';
        if (isset($ctgLists[$parentId])) {
            if ($parentId != 0) {
                $html = '<ul>';
            }
            foreach ($ctgLists[$parentId] as $childId) {
                $html .= '<li><a href="' . $ctgData[$childId]['lurl'] . '">' . convert_to_uppercase($ctgData [$childId]['lname']) . '</a>';
                if ($parentId != 0) {
                    $html .= menu_single($childId, $ctgLists, $ctgData);
                }

                $html .= '</li>';
            }
            if ($parentId != 0) {
                $html .= '</ul>';
            }
        }

        return $html;
    }

}

if (!function_exists('multilevelMenu_type1')) {

    function multilevelMenu_type1($parentId, $ctgLists, $ctgData) {
        $html = '';       // stores and returns the html code with Menu lists
// if parent item with child IDs in ctgLists

        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $class_ul = '';
            } else {
                $class_ul = ' class="cont"';
            }
            if ($parentId != 0) {
                $html = "<ul $class_ul>";
            }
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    if (isset($ctgLists[$childId])) {
                        $more = ' <i class="fa fa-caret-down"></i>';
                    } else {
                        $more = '';
                    }
                    $clsl = ' class="dropdown"';       // class for anchors in main /first categories
                    $clsa = ' class="dropdown-toggle" data-toggle="dropdown"';       // class for anchors in main /first categories
                } else if (isset($ctgLists[$childId])) {
                    $more = '';
                    $clsl = ' class=""';       // class for anchors in main /first categories


                    $clsa = ' class="dropdown-toggle" data-toggle="dropdown"';       // class for anchors in lists with childs
                } else {
                    $more = '';
                    $clsl = '';       // class for anchors in main /first categories
                    $clsa = '';
                }

                $active = ($ctgData[$childId]['lurl'] == current_url()) ? ' is-act' : '';

                if ($parentId == 0) {
                    $html .= '<h4 class="heading1' . $active . '">' . $ctgData[$childId]['lname'] . '</h4>';

                    $html .= multilevelMenu_type1($childId, $ctgLists, $ctgData);     // re-calls the function to find parent with child-items recursively
                } else {// open LI
                    $html .= '<li><a href="' . $ctgData[$childId]['lurl'] . '" title="' . $ctgData[$childId]['lname'] . '">' . $ctgData[$childId]['lname'] . '</a>';

                    $html .= multilevelMenu_type1($childId, $ctgLists, $ctgData);     // re-calls the function to find parent with child-items recursively

                    $html .= '</li>';      // close LI
                }
            } if ($parentId != 0) {
                $html .= '</ul>';       // close UL
            }
        }

        return $html;
    }

}

if (!function_exists('multilevelMenu')) {

    function multilevelMenu($parentId, $ctgLists, $ctgData, $active_page = '') {
        $html = '';
        if (isset($ctgLists[$parentId])) {
            if ($parentId == 0) {
                $class_ul = ' class=""';
            } else {
                $class_ul = ' class="smenu"';
            }
            if ($parentId != 0) {
                $html = "<ul>";
            }
            foreach ($ctgLists[$parentId] as $childId) {
                $class_has_sub = '';
                if(isset($ctgLists[$childId])){
                    $class_has_sub = 'has-sub';
                }
                $more = '<i class="fas fa-chevron-right"></i> ';
                $clat = $class_has_sub . (($ctgData[$childId]['lurl'] == current_url() || $ctgData[$childId]['lurl'] == $active_page) ? ' active' : '');
                $html .= '<li' . (trim($clat) != '' ? (' class="' . $clat . '"') : '') . '><a href="' . $ctgData[$childId]['lurl'] . '"><span>' . $more . $ctgData[$childId]['lname'] . '</span></a>';

                $html .= multilevelMenu($childId, $ctgLists, $ctgData, $active_page);

                $html .= '</li>';
            }
            if ($parentId != 0) {
                $html .= '</ul>';
            }
        }

        return $html;
    }

}

if (!function_exists('multilevelOption')) {

    function multilevelOption($parentId, $ctgLists, $ctgData, $depth, $catid) {
        $char = '&brvbar;&rarr;';
        $html = '';

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists[$childId])) {
                    $tab = str_repeat($char, $depth);
                } else {
                    $tab = str_repeat($char, $depth);
                }

                $radio_checked = '';
                if ($ctgData[$childId] ['lurl'] === $catid) {
                    $radio_checked = ' selected="selected"';
                }

                $html .= "\n";
                $html .= '<option' . $radio_checked . ' value="' . $ctgData[$childId] ['lurl'] . '" title="' . $ctgData[$childId]['lname'] . '"' . '>' . $tab . $ctgData[$childId]['lname'] . '</option>';
                $html .= multilevelOption($childId, $ctgLists, $ctgData, $depth + 1, $catid);
            }
        }

        return $html;
    }

}
if (!function_exists('multilevelOptionParentid')) {

    function multilevelOptionParentid
    ($parentId, $ctgLists, $ctgData, $depth, $catid, $pid) {
        $char = '&brvbar;&rarr;';
        $html = '';

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {

                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists[$childId])) {
                    $tab = str_repeat($char, $depth);
                } else {
                    $tab = str_repeat($char, $depth);
                }

                $selected = '';
                if ($ctgData[$childId]['lurl'] === $pid) {
                    $selected = ' selected="selected"';
                }

                if ($ctgData[$childId]['lurl'] != $catid) {
                    $html .= "\n";
                    $html .= '<option' . $selected . ' value="' . $ctgData[$childId]['lurl'] . '"' . '>' . $tab . $ctgData[$childId]['lname'] . '</option>';
                    $html .= multilevelOptionParentid($childId, $ctgLists, $ctgData, $depth + 1, $catid, $pid);
                }
            }
        }

        return $html;
    }

}

if (!function_exists('multilevel_option_parent')) {

    function multilevel_option_parent($parentId, $ctgLists, $ctgData, $depth, $catid, $pid) {
        $char = '&brvbar;&rarr;';
        $html = '';

        if (isset($ctgLists [$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists [$childId])) {
                    $tab = str_repeat($char, $depth);
                } else {
                    $tab = str_repeat($char, $depth);
                } $selected = '';
                if ($ctgData[$childId]['lid'] === $pid) {
                    $selected = ' selected="selected"';
                }


                if ($ctgData[$childId]['lid'] != $catid) {
                    $html .= "\n";
                    $html .= '<option' . $selected . ' value="' . $ctgData[$childId]['lid'] . '"' . '>' . $tab . $ctgData[$childId]['lname'] . '</option>';
                    $html .= multilevel_option_parent($childId, $ctgLists, $ctgData, $depth + 1, $catid, $pid);
                }
            }
        }

        return $html;
    }

}

if (!function_exists('multilevelCheckbox')) {

    function multilevelCheckbox($parentId, $ctgLists, $ctgData, $depth, $checkedLists, $catid) {
        $char = "&nbsp;";
        $html = '';

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists[$childId])) {

                    $tab = str_repeat($char, $depth * 6);
                } else {
                    $tab = str_repeat($char, $depth * 6);
                }

                $checked = '';
                if (in_array($ctgData[$childId]['lurl'], $checkedLists)) {
                    $checked = ' checked="checked"';
                }

                $radio_checked = '';
                if ($ctgData [$childId] ['lurl'] === $catid) {
                    $radio_checked = ' checked="checked"';
                }

                $html .= "\n";
                $html .= "<tr>";
                $html .= '<td>' . $tab . '<input type="checkbox"' . $checked . ' value="' . $ctgData[$childId]['lurl'] . '" name="catids[]" />' . $char . $ctgData[$childId]['lname'] . '</td>';
                $html .= '<td><input id="catright_' . $ctgData[$childId]['lurl'] . '" style=" display: none;" type="radio" name="catid" title="Chủ đề chính cho bài viết"' . $radio_checked . ' value="' . $ctgData[$childId]['lurl'] . '" /></td>';
                $html .= "</tr>";
                $html .= multilevelCheckbox($childId, $ctgLists, $ctgData, $depth + 1, $checkedLists, $catid);
            }
        }

        return $html;
    }

}

if (!function_exists('multilevelSelectbox')) {

    function multilevelSelectbox($parentId, $ctgLists, $ctgData, $depth, $catid) {
        $char = '&brvbar;&rarr;';
        $html = '';

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists[$childId])) {
                    $tab = str_repeat($char, $depth * 1);
                } else {
                    $tab = str_repeat($char, $depth * 1);
                }

                $selected = '';
                if ($ctgData[$childId]['lid'] === $catid) {
                    $selected = ' selected="selected"';
                }
                $html .= "\n";
                $html .= '<option' . $selected . ' value="' . $ctgData[$childId]['lid'] . '"' . '>' . $tab . $ctgData[$childId]['lname'] . '</option>';
                $html .= multilevelSelectbox($childId, $ctgLists, $ctgData, $depth + 1, $catid);
            }
        }

        return $html;
    }

}

if (!function_exists('multilevel_cat')) {

    function multilevel_cat($parentId, $ctgLists, $ctgData, $depth, $catid) {
        $char = '&brvbar;&rarr;';
        $html = '';

        if (isset($ctgLists[$parentId])) {
            foreach ($ctgLists[$parentId] as $childId) {
                if ($parentId == 0) {
                    $tab = '';
                } else if (isset($ctgLists[$childId])) {
                    $tab = str_repeat($char, $depth * 1);
                } else {
                    $tab = str_repeat($char, $depth * 1);
                }

                $selected = '';
                if ($ctgData[$childId]['lid'] === $catid) {
                    $selected = ' selected="selected"';
                }
                $html .= "\n";
                $html .= '<option' . $selected . ' value="' . $ctgData[$childId]['lid'] . '"' . '>' . $tab . $ctgData[$childId]['lname'] . '</option>';
                $html .= multilevel_cat($childId, $ctgLists, $ctgData, $depth + 1, $catid);
            }
        }

        return $html;
    }

}
//Lê Văn Nhàn
/* End of file menu_helper.php */
/* Location: ./application/helpers/menu_helper.php */
