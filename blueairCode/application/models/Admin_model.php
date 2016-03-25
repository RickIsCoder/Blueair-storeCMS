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
            $query = "select product.productID as productID, productName, not isnull(privilege.productID) as isOnline from product left join privilege on product.productID = privilege.productID and privilege.userID = ? where product.serieID = ?";
            $products = $this->db->query($query, array($_SESSION["userID"], $serieId))->result_array();
        }
        else if($_SESSION['userType'] == 1){
            $query = "select productID, productName from product where serieID = ?";
            $products = $this->db->query($query, $serieId)->result_array();
        }
        else{
            return FALSE;
        }
        foreach ($products as &$production) {
            $pics = $pic->getProductionPic($production['productID']);
            if($_SESSION['userType'] == 2){
                $production['isOnline'] = $production['isOnline'] == '1' ? TRUE : FALSE;
            }
            if(count($pics) > 0){
                $production['picture_path'] = base_url("/upload/" . $pics[0]["pic_path"]);
            }
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
	
	public function getProductionList($serieID){
		switch ($_SESSION['userType']) {
			case '1':
				$production_sql = 'SELECT productName,productID FROM product WHERE serieID = ?';
				$data = $this->db->query($production_sql,$serieID)->result_array();
				return $data;
				break;

			case '2':
				$production_sql = 'SELECT productName,productID FROM product WHERE serieID = ? AND productID IN (SELECT productID FROM privilege WHERE userID = )';
				$data = $this->db->query($production_sql,array($serieID, $_SESSION['userID']))->result_array();
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
		return  $productID;
	}

	public function delProduction($id){
		$this->load->model('Pic_model','pic');
		$relativePicID = $this->pic->relativePicID($id);
		foreach ($relativePicID as $pic) {
        	$this->pic->delProductionPic($pic['pictureID']);
		}

		//删除 参数 特性 tips
		$this->db->where('productID', $id);
		$this->db->delete('privilege');

		$this->db->where('productID', $id);
		$this->db->delete('product');

		$this->db->where('productID', $id);
		$this->db->delete('feature');

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