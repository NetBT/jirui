<div class="main-page col-xs-12 ">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>配置管理</b>Configuration Management</div>
        <div class="header-search col-xs-8">
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">

        <div id="tab-system" class="HuiTab">
            <div class="tabBar cl">
                <span>规则管理</span>
                <span>拍摄日期</span>
                <span>订单条目</span>
                <span>套系条目</span>
                <span>客户来源</span>
                <span>门市收款</span>
                <span>支付方式</span>
            </div>
            <div class="tabCon margin-top-10">
                <form name="rule_management" id="rule_management">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <!--                <thead>-->
                        <!--                <tr role="row">-->
                        <!--                    <th style="width:15%;" class="text-left padding-left-15"><strong>配置项目</strong></th>-->
                        <!--                    <th style="width:60%" class="text-left padding-left-15"><strong>配置值</strong></th>-->
                        <!--                </tr>-->
                        <!--                </thead>-->
                        <tbody>
                        <tr>
                            <td class="text-left font-sm" rowspan="2"><label>未付款提醒</label></td>
                            <td class="text-left">
                                <span>未付全款提醒</span>
                                <input type="type" value="<?= $list['not_payment_full']?>" class="input-text" style="width:20%" name="not_payment_full">天
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>未付尾款提醒</span>
                                <input type="type" value="<?= $list['not_payment_tail']?>" class="input-text" style="width:20%" name="not_payment_tail">天
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" rowspan="3"><label>排项提示规则管理</label></td>
                            <td class="text-left">
                                <span>撞项提示</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['rush_on_off'] == 1) : ?>checked<?php endif;?> name="rush_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['rush_on_off'] == 2) : ?>checked<?php endif;?> name="rush_on_off" value="2">关闭</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>改期提示</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['change_date_on_off'] == 1) : ?>checked<?php endif;?> name="change_date_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['change_date_on_off'] == 2) : ?>checked<?php endif;?> name="change_date_on_off" value="2">关闭</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>未定拍摄时间提示</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['not_shooting_time_on_off'] == 1) : ?>checked<?php endif;?> name="not_shooting_time_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['not_shooting_time_on_off'] == 2) : ?>checked<?php endif;?> name="not_shooting_time_on_off" value="2">关闭</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" rowspan="4"><label>后期提醒规则</label></td>
                            <td class="text-left">
                                <span>逾期未选片提醒</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['overdue_not_select_pic_on_off'] == 1) : ?>checked<?php endif;?> name="overdue_not_select_pic_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['overdue_not_select_pic_on_off'] == 2) : ?>checked<?php endif;?> name="overdue_not_select_pic_on_off" value="2">关闭</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>逾期未回件提醒</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['overdue_not_response_on_off'] == 1) : ?>checked<?php endif;?> name="overdue_not_response_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['overdue_not_response_on_off'] == 2) : ?>checked<?php endif;?> name="overdue_not_response_on_off" value="2">关闭</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>逾期未取成品提醒</span>
                                <input type="type" value="<?= $list['overdue_get_product']?>" class="input-text" style="width:20%" name="overdue_get_product">天
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>成品缺货提醒</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['product_stockout_on_off'] == 1) : ?>checked<?php endif;?> name="product_stockout_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['product_stockout_on_off'] == 2) : ?>checked<?php endif;?> name="product_stockout_on_off" value="2">关闭</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" rowspan="4"><label>后期规则</label></td>
                            <td class="text-left">
                                <span>普修</span>
                                <input type="type" value="<?= $list['general_mend']?>" class="input-text" style="width:20%" name="general_mend">天
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>精修</span>
                                <input type="type" value="<?= $list['exquisite_mend']?>" class="input-text" style="width:20%" name="exquisite_mend">天
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>排版设计</span>
                                <input type="type" value="<?= $list['composing_design']?>" class="input-text" style="width:20%" name="composing_design">天
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>制作</span>
                                <input type="type" value="<?= $list['make_production']?>" class="input-text" style="width:20%" name="make_production">天
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>二销</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['second_sell_on_off'] == 1) : ?>checked<?php endif;?> name="second_sell_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['second_sell_on_off'] == 2) : ?>checked<?php endif;?> name="second_sell_on_off" value="2">关闭</label>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#rule_management')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="shoot_date" id="shoot_date" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <?php if(isset($list['plan_time_slot']) && !empty($list['plan_time_slot'])):?>
                            <?php foreach ($list['plan_time_slot'] as $key => $value) :?>

                                <tr id="dateTr_<?= $key?>">
                                    <td class="text-left font-sm" width="93" style="padding:10px 0" ><label>拍摄时间段<?= $key?></label></td>
                                    <td class="text-left" width="226" style="padding:10px 0">
                                        <input type="text" style="width: 100px"  class="input-text date" value="<?= $value['start']?>" id="startTime_<?= $key?>" placeholder="开始时间">
                                        <span class="text-right text-primary">至</span>
                                        <input type="text" style="width: 100px" class="input-text date" value="<?= $value['end']?>" id="endTime_<?= $key?>" placeholder="结束时间">
                                    </td>
                                    <td class="text-left font-sm" id="button_<?= $key?>">
                                        <?php if($key == count($list['plan_time_slot'])):?>
                                            <button type="button" class="btn btn-warning size-MINI margin-left-30" onclick="addSingleDate()">添加时间段</button>
                                            <button type="button" class="btn btn-danger size-MINI" onclick="subSingleDate()">删除时间段</button>
                                        <?php endif;?>

                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>

                        <tfoot>
                        <tr>
                            <td class=""></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSaveDate(this)"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                            <td class=""></td>
                        </tr>
                        </tfoot>

                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="order_entry" id="order_entry" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>

                        <tr>
                            <td class="text-left font-sm"><label>订单编号方式</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['member_order_coded_system'] == 1) : ?>checked<?php endif;?> name="member_order_coded_system" value="1">系统生成</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_order_coded_system'] == 2) : ?>checked<?php endif;?> name="member_order_coded_system" value="2">手动填入</label>
                                <span class="text-hot font-sm margin-left-30">添加订单时的订单编码生成方式</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>订单编号</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_number_on_off'] == 1) : ?>checked<?php endif;?> name="order_number_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_number_on_off'] == 2) : ?>checked<?php endif;?> name="order_number_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>订单日期</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_date_on_off'] == 1) : ?>checked<?php endif;?> name="order_date_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_date_on_off'] == 2) : ?>checked<?php endif;?> name="order_date_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>宝宝姓名</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_member_name_on_off'] == 1) : ?>checked<?php endif;?> name="order_member_name_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_member_name_on_off'] == 2) : ?>checked<?php endif;?> name="order_member_name_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>成单价</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_price_on_off'] == 1) : ?>checked<?php endif;?> name="order_price_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_price_on_off'] == 2) : ?>checked<?php endif;?> name="order_price_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>定金</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_earnest_on_off'] == 1) : ?>checked<?php endif;?> name="order_earnest_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_earnest_on_off'] == 2) : ?>checked<?php endif;?> name="order_earnest_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>数量</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_num_on_off'] == 1) : ?>checked<?php endif;?> name="order_num_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_num_on_off'] == 2) : ?>checked<?php endif;?> name="order_num_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>折扣</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_discount_on_off'] == 1) : ?>checked<?php endif;?> name="order_discount_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_discount_on_off'] == 2) : ?>checked<?php endif;?> name="order_discount_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>积分</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_integral_on_off'] == 1) : ?>checked<?php endif;?> name="order_integral_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_integral_on_off'] == 2) : ?>checked<?php endif;?> name="order_integral_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>尾款</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['order_tail_price_on_off'] == 1) : ?>checked<?php endif;?> name="order_tail_price_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['order_tail_price_on_off'] == 2) : ?>checked<?php endif;?> name="order_tail_price_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#order_entry')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="combo_entry" id="combo_entry" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm" rowspan="4"><label>套系条目</label></td>
                            <td class="text-left">
                                <span>套系名称</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_name_on_off'] == 1) : ?>checked<?php endif;?> name="combo_name_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_name_on_off'] == 2) : ?>checked<?php endif;?> name="combo_name_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>入底入册</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_volume_on_off'] == 1) : ?>checked<?php endif;?> name="combo_volume_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_volume_on_off'] == 2) : ?>checked<?php endif;?> name="combo_volume_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>套系售价</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_price_on_off'] == 1) : ?>checked<?php endif;?> name="combo_price_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_price_on_off'] == 2) : ?>checked<?php endif;?> name="combo_price_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>服装造型</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_clothing_on_off'] == 1) : ?>checked<?php endif;?> name="combo_clothing_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_clothing_on_off'] == 2) : ?>checked<?php endif;?> name="combo_clothing_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" rowspan="5"><label>套系商品条目</label></td>
                            <td class="text-left">
                                <span>商品名称</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_name_on_off'] == 1) : ?>checked<?php endif;?> name="combo_goods_name_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_name_on_off'] == 2) : ?>checked<?php endif;?> name="combo_goods_name_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>商品数量</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_num_on_off'] == 1) : ?>checked<?php endif;?> name="combo_goods_num_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_num_on_off'] == 2) : ?>checked<?php endif;?> name="combo_goods_num_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>商品规格</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_rule_on_off'] == 1) : ?>checked<?php endif;?> name="combo_goods_rule_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_rule_on_off'] == 2) : ?>checked<?php endif;?> name="combo_goods_rule_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>照片编号</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_pic_number_on_off'] == 1) : ?>checked<?php endif;?> name="combo_goods_pic_number_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_pic_number_on_off'] == 2) : ?>checked<?php endif;?> name="combo_goods_pic_number_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span>默认P数</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_default_p_on_off'] == 1) : ?>checked<?php endif;?> name="combo_goods_default_p_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['combo_goods_default_p_on_off'] == 2) : ?>checked<?php endif;?> name="combo_goods_default_p_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加订单是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#combo_entry')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="client_source" id="client_source" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm"><label>老顾客推荐</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_lgh_on_off'] == 1) : ?>checked<?php endif;?> name="client_source_lgh_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_lgh_on_off'] == 2) : ?>checked<?php endif;?> name="client_source_lgh_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加用户是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>自然进店</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_natural_on_off'] == 1) : ?>checked<?php endif;?> name="client_source_natural_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_natural_on_off'] == 2) : ?>checked<?php endif;?> name="client_source_natural_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加用户是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>电话咨询</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_tel_on_off'] == 1) : ?>checked<?php endif;?> name="client_source_tel_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_tel_on_off'] == 2) : ?>checked<?php endif;?> name="client_source_tel_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加用户是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>大众点评</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_dzdp_on_off'] == 1) : ?>checked<?php endif;?> name="client_source_dzdp_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_dzdp_on_off'] == 2) : ?>checked<?php endif;?> name="client_source_dzdp_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加用户是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>推广商</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_tgs_on_off'] == 1) : ?>checked<?php endif;?> name="client_source_tgs_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_tgs_on_off'] == 2) : ?>checked<?php endif;?> name="client_source_tgs_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加用户是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>其他</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_qt_on_off'] == 1) : ?>checked<?php endif;?> name="client_source_qt_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['client_source_qt_on_off'] == 2) : ?>checked<?php endif;?> name="client_source_qt_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">添加用户是否显示该字段【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>推荐返利</label></td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['recommend_rebate_money']?>" class="input-text" style="width:20%" name="recommend_rebate_money">元<span class="text-hot font-sm margin-left-30">推荐者可以获得的金额</span>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#client_source')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="ab_receipt" id="ab_receipt" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm"><label>定金</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_earnest_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_earnest_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_earnest_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_earnest_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>尾款</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_tail_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_tail_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_tail_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_tail_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>套系升级</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_combo_update_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_combo_update_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_combo_update_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_combo_update_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>服装造型补款</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_clothing_repair_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_clothing_repair_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_clothing_repair_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_clothing_repair_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>加片</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_add_pic_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_add_pic_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_add_pic_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_add_pic_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>打包底片</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_packaging_film_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_packaging_film_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_packaging_film_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_packaging_film_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>相册加P</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_photo_p_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_photo_p_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_photo_p_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_photo_p_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>退款</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_refund_on_off'] == 1) : ?>checked<?php endif;?> name="ab_receipt_refund_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_receipt_refund_on_off'] == 2) : ?>checked<?php endif;?> name="ab_receipt_refund_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">门市收款是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#ab_receipt')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="pay_type" id="pay_type" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm"><label>现金</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_cash_on_off'] == 1) : ?>checked<?php endif;?> name="pay_cash_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_cash_on_off'] == 2) : ?>checked<?php endif;?> name="pay_cash_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>银行卡</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_bank_card_on_off'] == 1) : ?>checked<?php endif;?> name="pay_bank_card_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_bank_card_on_off'] == 2) : ?>checked<?php endif;?> name="pay_bank_card_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>微信</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_wechat_on_off'] == 1) : ?>checked<?php endif;?> name="pay_wechat_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_wechat_on_off'] == 2) : ?>checked<?php endif;?> name="pay_wechat_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>支付宝</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_alipay_on_off'] == 1) : ?>checked<?php endif;?> name="pay_alipay_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_alipay_on_off'] == 2) : ?>checked<?php endif;?> name="pay_alipay_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>信用卡</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_credit_card_on_off'] == 1) : ?>checked<?php endif;?> name="pay_credit_card_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_credit_card_on_off'] == 2) : ?>checked<?php endif;?> name="pay_credit_card_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>网店</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_online_store_on_off'] == 1) : ?>checked<?php endif;?> name="pay_online_store_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_online_store_on_off'] == 2) : ?>checked<?php endif;?> name="pay_online_store_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-sm"><label>其他</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_other_on_off'] == 1) : ?>checked<?php endif;?> name="pay_other_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['pay_other_on_off'] == 2) : ?>checked<?php endif;?> name="pay_other_on_off" value="2">关闭</label>
                                <span class="text-hot font-sm margin-left-30">支付方式是否显示该项【开启则是显示，关闭则是隐藏】</span>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#pay_type')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $('#test123').colorpicker({
                format : "hex",
            });

            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
            $("#tab-system").Huitab({
                index:0
            });

            operationDate();

        });

        function operationDate()
        {
            $(".date").on('click', function() {
                WdatePicker({
                    readOnly: true,
                    dateFmt: 'HH:mm',
//                    minDate:'08:00:00',
//                    maxDate:'18:00:00',
                    quickSel:['%H-00-00','%H-15-00','%H-30-00','%H-45-00'],
//                    minDate:'%y-%M-%d 00:00:00'
                });
            });
        }

        /**
         * 保存修改操作
         */
        function doSave(formID)
        {
            var option = ({
                url : '<?= \yii\helpers\Url::to(['system-manage/do-save-a-b'])?>',
                type : 'POST',
                async: false,
                dataType : 'JSON',
                success: function(data)
                {
                    var callBackFunction = '';
                    if(data.code == 1000)
                    {
                        layer.closeAll('page');
                        callBackFunction  = DataTable.drawTable();
                    }
                    layer.msg(data.message,{icon:6,time:2000},callBackFunction);
                },
                error: function()
                {
                    layer.msg('网络错误',{icon:5,time:2000});
                }
            });
            $(formID).ajaxSubmit(option);
        }

        /**
         * 保存时间段
         */
        function doSaveDate()
        {
            shootDate._init();
            ajaxSubmit('<?= \yii\helpers\Url::to(['system-manage/do-save-a-b-shoot-date'])?>',{data:JSON.stringify(shootDate.totalDate)});

        }

        function addSingleDate()
        {
            var  dateNum = $('#shoot_date').find('tr','tbody').length;
            var singleDate = '<tr id="dateTr_'+dateNum+'">';
            singleDate += '<td class="text-left font-sm" width="93" style="padding:10px 0" ><label>拍摄时间段'+dateNum+'</label></td>';
            singleDate += ' <td class="text-left" width="226" style="padding:10px 0">';
            singleDate += ' <input type="text" style="width: 100px"  class="input-text date" id="startTime_'+dateNum+'" placeholder="开始时间">';
            singleDate += ' <span class="text-right text-primary">至</span>';
            singleDate += ' <input type="text" style="width: 100px" class="input-text date" id="endTime_'+dateNum+'" placeholder="结束时间">';
            singleDate += ' </td>';
            singleDate += ' <td class="text-left font-sm" id="button_'+dateNum+'">';
            singleDate += ' <button type="button" class="btn btn-warning size-MINI margin-left-30" onclick="addSingleDate()">添加时间段</button>';
            singleDate += ' <button type="button" class="btn btn-danger size-MINI" onclick="subSingleDate(this)">删除时间段</button>';
            singleDate += ' </td>';
            singleDate += '</tr>';

            $('tbody','#shoot_date').append(singleDate);

            //只保留最后一个添加 删除时间段按钮
            for(var i = 1; i < dateNum; i ++) {
                $('#button_'+i).html('');
            }
            operationDate();
        }

        function subSingleDate(obj)
        {
            var $obj = $(obj);
            var  dateNum = $('#shoot_date').find('tr','tbody').length;
            var buttonHtml = ' <button type="button" class="btn btn-warning size-MINI margin-left-30" onclick="addSingleDate()">添加时间段</button>';
            if(dateNum > 3) {
                buttonHtml += ' <button type="button" class="btn btn-danger size-MINI" onclick="subSingleDate(this)">删除时间段</button>';
            }
            $obj.parents('tr').prev().find('td').eq(2).html(buttonHtml);
            $obj.parents('tr').remove();

        }

        var shootDate = {
            singleDate : '',
            totalDate : [],

            _init : function() {
                var _this = this;
                $('tbody tr','#shoot_date').each(function(){
                    var btnId = $(this).attr('id').split('_');
                    var id =  btnId[1];
                    _this.singleDate = {
                        id : id,
                        start : $('#startTime_'+id).val(),
                        end : $('#endTime_'+id).val(),
                    };

                    _this.totalDate.push(_this.singleDate);
                });
            },
        };


    </script>