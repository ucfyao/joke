<?php
class Page{
	// 起始行数
    public $firstRow;
	//现在页数
	public $nowPage;
	//总页数
	public $totalPage;
	//总行数
	public $totalRows;
	//目录名
	public $dir;
	//分页的条数
	public $page_rows;
	//架构函数
	public function __construct($totalRows,$dir,$page_rows){
		$this->totalRows = $totalRows;
		$this->dir = $dir;
		$this->nowPage  = !empty($_GET['page']) ? intval($_GET['page']) : 1;
		if(empty($page_rows)){
			$page_rows = $GLOBALS['config']['page_rows'];
		}
		$this->page_rows = $page_rows;
		$this->totalPage = ceil($totalRows/$page_rows);
		if($this->nowPage > $this->totalPage && $this->totalPage>0){
			$this->nowPage = $this->totalPage;
		}
		$this->firstRow = $page_rows*($this->nowPage-1);
	}
    public function show(){
		if($this->totalRows == 0) return false;
		$now = $this->nowPage;
		$total = $this->totalPage;
		$dir = $this->dir;
		$site_url = $GLOBALS['config']['site_url'];
		$str = '';
		if($GLOBALS['config']['site_html']){
			if ($now == 2){
				$str .= '<a href="'.$site_url.'/'.$dir.'/" class="prev" title="上一页">上一页</a>';
			}else if($now > 2){
				$str .= '<a href="'.$site_url.'/'.$dir.'/'.($now-1).'" class="prev" title="上一页">上一页</a>';
			}
			if($now!=1 && $now>4 && $total>6){
				$str .= '<a href="'.$site_url.'/'.$dir.'/" title="1">1</a><div class="page-numbers dots">…</div>';
			}
			for($i=1;$i<=5;$i++){
				if($now <= 1){
					$page = $i;
				}elseif($now > $total-1){
					$page = $total-5+$i;
				}else{
					$page = $now-3+$i;
				}
				if($page != $now  && $page>0){
					if($page==1){
						$str .= '<a href="'.$site_url.'/'.$dir.'/" title="1">1</a>';
					}else if($page<=$total){
						$str .= '<a href="'.$site_url.'/'.$dir.'/'.($page).'/" title="第'.$page.'页">'.$page.'</a>';
					}else{
						break;
					}
				}else{
					if($page == $now) $str.='<span class="current">'.$page.'</span>';
				}
			}
			if($total != $now && $now<$total-2 && $total>5){
				$str .= '<span class="dots">…</span><a href="'.$site_url.'/'.$dir.'/'.($total).'/" title="第'.$total.'页">'.$total.'</a>';
			}
			if ($now != $total){
				$str .= '<a href="'.$site_url.'/'.$dir.'/'.($now+1).'/" class="next">下一页</a>';
			}
		}else{
			if ($now == 2){
				$str .= '<a href="'.U('Index/'.$dir).'" class="prev" title="上一页">上一页</a>';
			}else if($now > 2){
				$str .= '<a href="'.U('Index/'.$dir.'?page='.($now-1)).'" class="prev" title="上一页">上一页</a>';
			}
			if($now!=1 && $now>4 && $total>6){
				$str .= '<a href="'.U('Index/'.$dir).'" title="1">1</a><div class="page-numbers dots">…</div>';
			}
			for($i=1;$i<=5;$i++){
				if($now <= 1){
					$page = $i;
				}elseif($now > $total-1){
					$page = $total-5+$i;
				}else{
					$page = $now-3+$i;
				}
				if($page != $now  && $page>0){
					if($page==1){
						$str .= '<a href="'.U('Index/'.$dir).'" title="1">1</a>';
					}else if($page<=$total){
						$str .= '<a href="'.U('Index/'.$dir.'?page='.$page).'" title="第'.$page.'页">'.$page.'</a>';
					}else{
						break;
					}
				}else{
					if($page == $now) $str.='<span class="current">'.$page.'</span>';
				}
			}
			if($total != $now && $now<$total-5 && $total>10){
				$str .= '<span class="dots">…</span><a href="'.U('Index/'.$dir.'?page='.$total).'" title="第'.$total.'页">'.$total.'</a>';
			}
			if ($now != $total){
				$str .= '<a href="'.U('Index/'.$dir.'?page='.($now+1)).'" class="next">下一页</a>';
			}
		}
		
		return $str;
    }
}
?>