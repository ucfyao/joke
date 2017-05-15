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
	//年
	public $year;
	//月
	public $month;
	//日
	public $day;
	//分页的条数
	public $page_rows;
	//架构函数
	public function __construct($totalRows,$year,$month,$day){
		$this->totalRows = $totalRows;
		$this->year = $year;
		$this->month = $month;
		$this->day = $day;
		$this->nowPage  = !empty($_GET['page']) ? intval($_GET['page']) : 1;
		$this->page_rows = $GLOBALS['config']['page_rows'];
		$this->totalPage = ceil($totalRows/$this->page_rows);
		if($this->nowPage > $this->totalPage && $this->totalPage>0){
			$this->nowPage = $this->totalPage;
		}
		$this->firstRow = $this->page_rows*($this->nowPage-1);
	}
    public function show(){
		if($this->totalRows == 0) return false;
		$now = $this->nowPage;
		$total = $this->totalPage;
		$year = $this->year;
		$month = $this->month;
		$day = $this->day;
		$site_url = $GLOBALS['config']['site_url'];
		$str = '';
		if($GLOBALS['config']['site_html']){
			if ($now == 2){
				$str .= '<a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/" class="prev" title="上一页">上一页</a>';
			}else if($now > 2){
				$str .= '<a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/'.($now-1).'/" class="prev" title="上一页">上一页</a>';
			}
			if($now!=1 && $now>4 && $total>6){
				$str .= '<a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/" title="1">1</a><div class="page-numbers dots">…</div>';
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
						$str .= '<a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/" title="1">1</a>';
					}else if($page<=$total){
						$str .= '<a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/'.($page).'/" title="第'.$page.'页">'.$page.'</a>';
					}else{
						break;
					}
				}else{
					if($page == $now) $str.='<span class="current">'.$page.'</span>';
				}
			}
			if($total != $now && $now<$total-2 && $total>5){
				$str .= '<span class="dots">…</span><a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/'.($total).'/" title="第'.$total.'页">'.$total.'</a>';
			}
			if ($now != $total){
				$str .= '<a href="'.$site_url.'/history/'.$year.'/'.$month.'/'.$day.'/'.($now+1).'/" class="next">下一页</a>';
			}
		}else{
			$dir = ACTION_NAME;
			if ($now == 2){
				$str .= '<a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day).'" class="prev" title="上一页">上一页</a>';
			}else if($now > 2){
				$str .= '<a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day.'&page='.($now-1)).'" class="prev" title="上一页">上一页</a>';
			}
			if($now!=1 && $now>4 && $total>6){
				$str .= '<a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day).'" title="1">1</a><div class="page-numbers dots">…</div>';
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
						$str .= '<a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day).'" title="1">1</a>';
					}else if($page<=$total){
						$str .= '<a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day.'&page='.$page).'" title="第'.$page.'页">'.$page.'</a>';
					}else{
						break;
					}
				}else{
					if($page == $now) $str.='<span class="current">'.$page.'</span>';
				}
			}
			if($total != $now && $now<$total-5 && $total>10){
				$str .= '<span class="dots">…</span><a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day.'&page='.$total).'" title="第'.$total.'页">'.$total.'</a>';
			}
			if ($now != $total){
				$str .= '<a href="'.U('History/index?year='.$year.'&month='.$month.'&day='.$day.'&page='.($now+1)).'" class="next">下一页</a>';
			}
		}
		
		return $str;
    }
}
?>