	<div class="mui-content">
			<div class="mui-card">
				<div class="mui-card-header mui-card-media">
					<img src="<?php echo tomedia($settings['brands']['logo']);?>" />
					<div class="mui-media-body">
						<?php echo $row['title'];?>
						<p><?php echo $row['author'];?> <?php if $row['createtime']?> 发表于 <?php echo date('Y年 m月 d日 H:i',$row['createtime']);?> <?php endif;?></p>
					</div>
				</div>
				<div class="mui-card-content" >
					<img src="<?php echo tomedia($row['thumb']);?>" alt="" width="100%" onerror="RepairMyImg(this)" />
				</div>
				<div class="mui-card-content-inner">
						<p style="color: #333;"><?php echo $row['description'];?></p>
					</div>
				<div class="mui-card-footer">
					<a class="mui-card-link"><i class="mui-icon-extra mui-icon-extra-heart"></i>  <span class="mui-badge mui-badge-warning mui-badge-inverted"><?php echo $row['viewcount'];?></span></a>
					<a class="mui-card-link"><i class="mui-icon mui-icon-eye"></i>  <span class="mui-badge mui-badge-warning mui-badge-inverted"><?php echo $row['click'];?></span></a>
					<a class="mui-card-link" href="<?php echo fm_murl('article','detail','index',array('id'=>$row['id'],'sn'=>''));?>"><i class="mui-icon mui-icon-more"></i>详情</a>
				</div>
			</div>
		</div>
