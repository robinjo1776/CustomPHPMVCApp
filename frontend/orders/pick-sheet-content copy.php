<style>
    @media print {
        #footer {
            display: none;
        }
    }
</style>
<table style="
            width: 1000px;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            border-top: 0px solid #000;
            border-right: 0px solid #000;
            border-bottom: 0px solid #000;
            border-left: 0px solid #000;
            vertical-align: top;
        ">
    <tr>
        <td style="
                padding-left: 0;
                padding-right: 0;
                padding-top: 0;
                padding-bottom: 0;
                border-top: 0px solid #000;
                border-left: 0px solid #000;
                border-right: 0px solid #000;
                border-bottom: 0px solid #000;
                font-size: 15px;
                border-collapse: collapse;
                vertical-align: top;
                ">
            <table style="
                        padding-left: 0;
                        padding-right: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                        border-collapse: collapse;
                        width: 100%;
                        margin-bottom: 20px;
                ">
                <tr>
                    <td style="
                            padding-left: 0;
                            padding-right: 0;
                            padding-top: 0;
                            padding-bottom: 0;
                            border-top: 0px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 0px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            ">
                        Winter's Turkeys
                    </td>
                    <td style="
                            padding-left: 0;
                            padding-right: 0;
                            padding-top: 0;
                            padding-bottom: 0;
                            border-top: 0px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 0px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            text-align: center;
                            font-weight: bold;
                            ">
                        Pallet Sheet for Order: OR<?php echo $orderDetails["orderno"]; ?>
                    </td>
                    <td style="
                            padding-left: 0;
                            padding-right: 0;
                            padding-top: 0;
                            padding-bottom: 0;
                            border-top: 0px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 0px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            text-align: right;
                            ">
                        <?php echo date("Y/m/d", strtotime($orderDetails["created_at"])); ?>
                    </td>

                </tr>
            </table>
            <table style="
                        padding-left: 0;
                        padding-right: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                        border-collapse: collapse;
                        width: 100%;
                        margin-bottom: 6px;
                ">
                <tr>
                    <td style="
                            padding-left: 0;
                            padding-right: 0;
                            padding-top: 0;
                            padding-bottom: 0;
                            border-top: 0px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 0px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            ">
                        <?php echo (!empty($orderDetails['customer'])) ? $orderDetails['customer'] : ""; ?>
                    </td>
                </tr>
            </table>
            <table style="
                        padding-left: 0;
                        padding-right: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                        border-collapse: collapse;
                        width: 100%;
                        margin-bottom: 20px;
                ">
                <tr>
                    <td style="
                            padding-left: 0;
                            padding-right: 0;
                            padding-top: 0;
                            padding-bottom: 0;
                            border-top: 0px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 0px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            ">
                        <?php echo (!empty($orderDetails['ord_address1'])) ? $orderDetails['ord_address1'] . "<br>" : ""; ?>
                        <?php echo (!empty($orderDetails['ord_address2'])) ? $orderDetails['ord_address2'] . "<br>" : ""; ?>
                        <?php echo (!empty($orderDetails['ord_city'])) ? $orderDetails['ord_city'] . ", " : ""; ?>
                        <?php echo (!empty($orderDetails['ord_province'])) ? $orderDetails['ord_province'] . ", " : ""; ?>
                        <?php echo (!empty($orderDetails['ord_postal_code'])) ? $orderDetails['ord_postal_code'] . "<br>" : ""; ?>

                    </td>
                </tr>
            </table>

            <?php
            if (!empty($orderItems)) {
                $categotyItem = [];
                $orderItemsArr = [];
                $itemRow = 0;
                while ($row = mysqli_fetch_assoc($orderItems)) {
                    $orderItemsArr[] = $row;
                }
                foreach ($orderItemsArr as $rkey => $row) {
            ?>
                    <?php
                    if (!in_array($row["cat_code"], $categotyItem)) {
                        $categotyItem[] = $row["cat_code"];
                        $itemTotalQty = $row['tq'];
                        $itemTotalWeight = $row['tw'];
                    ?>
                        <table style="
                        border-left: 1px solid #000;
                        border-right: 1px solid #000;
                        padding-left: 0;
                        padding-right: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                        border-collapse: collapse;
                        width: 100%;
                ">
                            <tbody>
                                <tr>
                                    <td style="
                            padding-left: 7px;
                            padding-right: 7px;
                            padding-top: 7px;
                            padding-bottom: 7px;
                            border-top: 1px solid #000;
                            border-left: 1px solid #000;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            width: 230px;
                            ">
                                        <?php echo $row["cat_code"]; ?>
                                    </td>
                                    <td colspan="2" style="
                            padding-left: 7px;
                            padding-right: 7px;
                            padding-top: 7px;
                            padding-bottom: 7px;
                            border-top: 1px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 1px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            ">
                                        <?php echo $row["cat_des"]; ?>
                                    </td>
                                    <td style="
                            padding-left: 7px;
                            padding-right: 7px;
                            padding-top: 7px;
                            padding-bottom: 7px;
                            border-top: 1px solid #000;
                            border-left: 1px solid #000;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            width: 230px;
                            ">
                                        5.00 - 6.99 kg
                                    </td>
                                </tr>
                            <?php } else {
                            $itemTotalQty += $row['tq'];
                            $itemTotalWeight += $row['tw'];
                        }
                            ?>
                            <?php if ($itemRow == 0) { ?>
                                <tr>
                                <?php } ?>
                                <td style="
                    padding-left: 7px;
                    padding-right: 7px;
                    padding-top: 7px;
                    padding-bottom: 7px;
                    border-top: 1px solid #000;
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    border-bottom: 1px solid #000;
                    font-size: 15px;
                    border-collapse: collapse;
                    vertical-align: top;
                    width: 230px;
                    ">
                                    <div class="">
                                        <table>
                                            <tr>
                                                <td style="width: 115px; text-align: right;"><?php echo $row["tw"]; ?></td>
                                                <td style="width: 115px; text-align: right;"><?php echo $row["tq"]; ?></td>
                                            </tr>
                                        </table>
                                        <!-- <div style="display: block; width: 115px; text-align: right;  border: 1px solid green;"></div>
                                        <div style="display: block; width: 115px; text-align: right; border: 1px solid green;"><?php echo $row["tq"]; ?></div> -->
                                    </div>
                                </td>
                                <?php
                                $itemRow++;

                                if ((isset($orderItemsArr[$rkey + 1]) && $row["cat_code"] != $orderItemsArr[$rkey + 1]["cat_code"]) || $rkey == count($orderItemsArr) - 1) {
                                    for ($i = $itemRow; $i < 4; $i++) {
                                ?>
                                        <td style="
                    padding-left: 7px;
                    padding-right: 7px;
                    padding-top: 7px;
                    padding-bottom: 7px;
                    border-top: 1px solid #000;
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    border-bottom: 1px solid #000;
                    font-size: 15px;
                    border-collapse: collapse;
                    vertical-align: top;
                    width: 230px;
                    "></td>
                                    <?php
                                        $itemRow++;
                                    }
                                    ?>
                                <?php } ?>
                                <?php
                                if ($itemRow == 4) {
                                    $itemRow = 0; ?>
                                </tr>
                            <?php } ?>

                            <?php if ((isset($orderItemsArr[$rkey + 1]) && $row["cat_code"] != $orderItemsArr[$rkey + 1]["cat_code"]) || $rkey == count($orderItemsArr) - 1) { ?>
                                <tr>
                                    <td style="
                            padding-left: 7px;
                            padding-right: 7px;
                            padding-top: 7px;
                            padding-bottom: 7px;
                            border-top: 1px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 1px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            width: 230px;
                            text-align: right;
                            ">
                                        5.00 - 6.99 kg
                                    </td>
                                    <td style="
                            padding-left: 7px;
                            padding-right: 7px;
                            padding-top: 7px;
                            padding-bottom: 7px;
                            border-top: 1px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 1px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            ">
                                        <div class="">
                                            <table>
                                                <tr>
                                                    <td style="width: 115px; text-align: right;"><?php echo $itemTotalWeight; ?></td>
                                                    <td style="width: 115px; text-align: right;"><?php echo $itemTotalQty; ?></td>
                                                </tr>
                                            </table>
                                            <!-- <div style="display: inline-block; width: 115px; text-align: right; border: 1px solid gold;"><?php echo $itemTotalWeight; ?></div>
                                            <div style="display: inline-block; width: 115px; text-align: right; border: 1px solid gold;"><?php echo $itemTotalQty; ?></div> -->
                                        </div>
                                    </td>
                                    <td colspan="2" style="
                            padding-left: 7px;
                            padding-right: 7px;
                            padding-top: 7px;
                            padding-bottom: 7px;
                            border-top: 1px solid #000;
                            border-left: 0px solid #000;
                            border-right: 0px solid #000;
                            border-bottom: 1px solid #000;
                            font-size: 15px;
                            border-collapse: collapse;
                            vertical-align: top;
                            font-weight: bold;
                            width: 230px;
                            text-align: left;
                            ">Total</td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="                       
                        margin-bottom: 15px;
                        width: 100%;
                        ">
                            <tbody>
                                <tr>
                                    <td style="
                        border-bottom: 2px solid #000;
                        padding-top: 8px;                        
                        padding-bottom: 8px; 
                        ">
                                        <div>
                                            <table style="width: 1000px;">
                                                <tr>
                                                    <td style="width: 40%; text-align: right;"><?php echo $row["cat_des"]; ?></td>
                                                    <td style="width: 20%; text-align: center;"><?php echo $itemTotalWeight; ?></td>
                                                    <td style="width: 40%; text-align: left;"><?php echo $itemTotalQty; ?></td>
                                                </tr>
                                            </table>
                                            <!-- <span style="display: inline-block; width: 50%; text-align: right;"><?php echo $row["cat_des"]; ?></span>
                                            <span style="display: inline-block; width: 10%; text-align: left; padding-left:5%;"><?php echo $itemTotalWeight; ?></span>
                                            <span style="display: inline-block; width: 32%; text-align: left;  padding-left:5%;"><?php echo $itemTotalQty; ?></span> -->
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
            <?php
                }
            }
            ?>
        </td>
    </tr>
</table>