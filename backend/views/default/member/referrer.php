<article class="page-container">
    <div class="vip">
        <div class="vip_box">
            <table class="top" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <th scope="col">序号</th>
                    <th scope="col">会员编号</th>
                    <th scope="col">姓名</th>
                    <th scope="col">性别</th>
                    <th scope="col">电话</th>
                    <th scope="col">年龄</th>
                    <th scope="col">微信</th>
                    <th scope="col">余额</th>
                    <th scope="col">积分</th>
                    <th scope="col">累计消费</th>
                </tr>
                <tr>
                    <td><?= $model->id?></td>
                    <td><?= $model->number?></td>
                    <td><?= $model->name?></td>
                    <td><?= $model->sex?></td>
                    <td><?= $model->tel?></td>
                    <td><?= $model->age?></td>
                    <td><?= $model->wechat?></td>
                    <td><?= $model->valid_money?></td>
                    <td><?= $model->integral?></td>
                    <td><?= $model->total_consume?></td>
                </tr>
                </tbody>
            </table>
            <div class="vip_box_bottom">
                <p class="bt">推荐会员信息</p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th scope="col">会员编号</th>
                        <th scope="col">会员姓名</th>
                        <th scope="col">余额</th>
                        <th scope="col">积分</th>
                        <th scope="col">创建时间</th>
                        <th scope="col">累计消费</th>
                    </tr>
                    <?php if(!empty($referrerInfo)) : ?>
                        <?php foreach ($referrerInfo as $key => $value) :?>
                            <tr>
                                <td style="border-left:none;"><?= $value['number']?></td>
                                <td><?= $value['name']?></td>
                                <td><?= $value['valid_money']?></td>
                                <td><?= $value['integral']?></td>
                                <td><?= date('Y-m-d',strtotime($value['create_time']))?></td>
                                <td><?= $value['total_consume']?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</article>