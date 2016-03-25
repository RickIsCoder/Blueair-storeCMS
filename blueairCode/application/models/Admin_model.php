<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Pic_model.php";

class Admin_model extends MY_Model{



	public function getSeriesList(){
		$data = $this->db->get('series')->result_array();
		//redis 缓存
		//$this->cache->redis->save('SeriesList', $data);
		return $data;
	}

	public function getSeriesName($serieID){
		$name_sql = 'SELECT serieName FROM series WHERE serieID = ?';
		$name = $this->db->query($name_sql,$serieID)->result_array();
		return $name;
	}

	public function getProductionName($productID){
		$name_sql = 'SELECT productName FROM product WHERE productID = ?';
		$name = $this->db->query($name_sql,$productID)->result_array();
		return $name;
	}
    
    public function getAdminProductsBySerieIs($serieId){
        $products = [];
        $pic = new Pic_model();
        if($_SESSION['userType'] == 2){
            $query = "select product.productID as productID, productName, isnull(privilege.productID) as online from product left join privilege on product.productID = privilege.productID where product.serieID = ? and privilege.userID = ?";
            $products = $this->db->query($query, array($serieId, $_SESSION["userID"]))->result_array();
        }
        else if($_SESSION['userType'] == 1){
            $query = "select productID, productName from product where serieID = ?";
            $products = $this->db->query($query, array($serieId)->result_array();
        }
        else{
            return FALSE;
        }
        foreach ($products as &$production) {
            $production['pic'] = $pic->getProductionPic($production['productID']);
        }
        return $products;
    }

	public function getAllProductionList(){
		$pic = new Pic_model();
		$serie_query = $this->db->get('series')->result_array();
		foreach ($serie_query as $v) {
			$production_sql='SELECT productName,productID FROM product WHERE serieID = ?';
			$v['production'] = $this->db->query($production_sql,$v['serieID'])->result_array();

			foreach ($v['production'] as &$production) {
				$production['pic'] = $pic->getProductionPic($production['productID']);
			}
			unset($production);
			$data[] = $v;
		}
		return $data;
	}

	public function getStoreProductionList($userID){
		$pic = new Pic_model();

		$serie_query = $this->db->get('series')->result_array();
		foreach ($serie_query as $v) {
			$not_exist='SELECT productName,productID FROM product WHERE serieID = '.$v['serieID'].' AND productID NOT IN (SELECT productID FROM privilege WHERE userID = '.$userID.')';
			$v['production_not_exist'] = $this->db->query($not_exist)->result_array();

			foreach ($v['production_not_exist'] as &$production) {
				$production['pic'] = $pic->getProductionPic($production['productID']);
			}
			unset($production);

			$exist='SELECT productName,productID FROM product WHERE serieID = '.$v['serieID'].' AND productID IN (SELECT productID FROM privilege WHERE userID = '.$userID.')';
			$v['production_exist'] = $this->db->query($exist)->result_array();
			
			foreach ($v['production_exist'] as &$production) {
				$production['pic'] = $pic->getProductionPic($production['productID']);
			}
			unset($production);

			$data[] = $v;
		}
		return $data;
	}
    
    
	public function getProductionList($serieID){
		switch ($_SESSION['userType']) {
			case '1':
				$production_sql = 'SELECT productName,productID FROM product WHERE serieID = ?';
				$data = $this->db->query($production_sql,$serieID)->result_array();
				return $data;
				break;

			case '2':
				$production_sql = 'SELECT productName,productID FROM product WHERE serieID = ?'.' AND productID IN (SELECT productID FROM privilege WHERE userID = '.$_SESSION['userID'].')';
				$data = $this->db->query($production_sql,$serieID)->result_array();
				return $data;

			default:
				return FALSE;
				break;
		}

		$production_sql = 'SELECT productName,productID FROM product WHERE serieID = ?';
		$data = $this->db->query($production_sql,$serieID)->result_array();
		return $data;
	}

	public function getProductionDetail($productID){
		$productDetail_sql = 'SELECT * FROM product WHERE productID = ?';
		$data = $this->db->query($productDetail_sql,$productID)->result_array();
		return $data;
	}

	public function addProduction($data){
		$this->db->insert('product', $data);
		$productID = $this->db->insert_id();
		//添加产品的同时 在表格中创建 description 和 label
		$des_data  = array('productID' => $productID,'description'=>'');
		$this->db->insert('description',$des_data);
		$label_data  = array('productID' => $productID,'label'=>'');
		$this->db->insert('label',$label_data);

		return  $productID;
	}

	public function delProduction($id){
		$this->load->model('Pic_model','pic');
		$relativePicID = $this->pic->relativePicID($id);
		foreach ($relativePicID as $pic) {
        	$this->pic->delProductionPic($pic['pictureID']);
		}

		//删除 参数 特性 label des tips
		$this->db->where('productID', $id);
		$this->db->delete('privilege');

		$this->db->where('productID', $id);
		$this->db->delete('product');

		$this->db->where('productID', $id);
		$this->db->delete('feature');

		$this->db->where('productID', $id);
		$this->db->delete('description');

		$this->db->where('productID', $id);
		$this->db->delete('label');

		$this->db->where('productID', $id);
		$this->db->delete('tips');

        return TRUE;
	}

	public function editProduction($id,$data){
		$this->db->where('productID', $id);
		$this->db->update('product', $data);
	}

	public function addSerie($data){
		$this->db->insert('series',$data);
		return $this->db->insert_id();
	}

	public function delSerie($serieID){
		$relativeProduction_sql = 'SELECT productID FROM product WHERE serieID = ?';
		$data = $this->db->query($relativeProduction_sql,$serieID)->result_array();
		foreach ($data as $relativeProduction) {
			$this->delProduction($relativeProduction['productID']);
		}

		$this->db->where('serieID', $serieID);
		$f = $this->db->delete('series');
		return $f;
	}

	public function delStoreProduction($data){
		$this->db->where($data);
		$F = $this->db->delete('privilege');
		return $F;
	}

	public function addStoreProduction($data){
		$F = $this->db->insert('privilege',$data);
		// echo $this->db->insert_id();
		return $F;
	}
}