<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/



$route['default_controller'] = 'welcome';
$route['404_override'] = 'errors/html/error_404';
$route['translate_uri_dashes'] = FALSE;


$route['loginout']= 'welcome/clearsession';
$route['login'] = 'user/login';
$route['verify'] = 'user/verify';

$route['cms'] = 'navigator/cms';
$route['adminCMS'] = 'backEnd/adminCMS';
$route['salesmanCMS'] = 'backEnd/salesmanCMS';


$route['auc'] = 'admin/admin/userCMS';
$route['productionCMS'] = 'admin/admin/productionCMS';
$route['asc'] = 'admin/admin/sceneCMS';
$route['au'] = 'admin/user/addUser';
$route['eu'] = 'admin/user/editUser';
$route['du'] = 'admin/user/delUser';
$route['pd'] = 'admin/admin/productionDetail';
$route['pd/(.*)'] = 'admin/admin/productionDetail/$1';
$route['ap'] = 'admin/admin/productionEdit';
$route['clear'] = 'admin/admin/clearSeesion';
$route['serieAdd'] = 'admin/admin/serieAdd';
$route['serieDel'] = 'admin/admin/serieDel';
$route['productionDel'] = 'admin/admin/productionDel';
$route['feature/(.*)'] = 'admin/feature/feature/$1';
//$route['upload']= 'admin/pic/upload'; BUG
$route['delPicture'] = 'admin/pic/delPicture';
$route['addFeature'] = 'admin/feature/addFeature';
$route['delFeature'] = 'admin/feature/delFeature';
$route['editFeature'] = 'admin/feature/editFeature';
$route['editDescription'] = 'admin/feature/editDescription';
$route['editLabel'] = 'admin/feature/editLabel';
$route['addTips'] = 'admin/feature/addTips';
$route['delTips'] = 'admin/feature/delTips';
$route['editTips'] = 'admin/feature/editTips';

$route['serieList'] = 'index/whatClear/serieList';
$route['compare'] = 'index/whatClear/compare';
$route['filter'] = 'index/whatClear/filter';
$route['help'] = 'index/whatClear/help';
$route['getScale'] = 'index/whatClear/getScale';
$route['advice'] = 'index/whatClear/advice';

$route['serieDetail/(.*)'] = 'index/whatClear/serieDetail/$1';
$route['productDeatail/(.*)'] = 'index/whatClear/productDeatail/$1';
$route['feature/(.*)'] = 'admin/feature/feature/$1';
$route['ifeature/(.*)'] = 'index/whatClear/feature/$1';
$route['parameter/(.*)'] = 'index/whatClear/parameter/$1';
$route['addCompare'] = 'index/whatClear/addCompare';
$route['delCompare'] = 'index/whatClear/delCompare';

$route['sceneAdd'] = 'admin/Scene/addScene';
$route['sceneDel'] = 'admin/Scene/delScene';
$route['sceneEdit'] = 'admin/Scene/editScene';

$route['scaleAdd'] = 'admin/Scene/addScale';
$route['scaleDel'] = 'admin/Scene/delScale';
$route['scaleEdit'] = 'admin/Scene/editScale';

$route['sceneProductionDel'] = 'admin/Scene/delSceneProduction';
$route['sceneProductionCheck'] = 'admin/Scene/checkSceneProduction';
$route['sceneProductionAdd'] = 'admin/Scene/addSceneProduction';



$route['whatClear'] = 'index/whatClear/index';
$route['whyBlueAir'] = 'index/whyBlueAir/index';