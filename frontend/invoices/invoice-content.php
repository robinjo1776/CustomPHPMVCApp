<style>
    @media print {
        #footer {
            display: none;
        }
    }
</style>
<table id="invoiceTable" style="width: 100%; margin-bottom: 120px;">
    <tr>
        <td style="text-align: left; vertical-align: top;">
            <h4 style="margin: 0 0 10px 0; font-weight: bold; font-size: 15px;">Winter's Turkeys</h4>
            <div style="font-size: 15px;">
                272039 TWP RD<br>
                Rockyview County AB T1X 2B2<br>
                laurel@wintersturkeys.ca<br>
                www.wintersturkeys.ca<br>
                GST Registration No.: 865238117RT0001<br>
            </div>
        </td>
        <td style="text-align: right; vertical-align: top;">
            <img style="width: auto;" src="<?php echo Helper::fullbaseUrl(); ?>images/invoice-logo.png" alt="logo" />
        </td>

    </tr>
    <tr>
        <td colspan="2">
            <h5 style="
                                    color: #012060;
                                    font-size: 15px;
                                    text-transform: uppercase;
                                    margin: 62px 0 20px 0;">
                Invoice
            </h5>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table style="width: 100%;">
                <tr>
                    <td style="vertical-align: top;">
                        <span style="color: #8c8f95; font-size: 15px;"> BILL TO</span>
                        <div style="color:#19000c">
                            <strong>
                                <?php echo (!empty($orderDetails['customer'])) ? $orderDetails['customer'] . "<br>" : ""; ?>
                                <?php echo (!empty($orderDetails['ord_address1'])) ? $orderDetails['ord_address1'] . "<br>" : ""; ?>
                                <?php echo (!empty($orderDetails['ord_address2'])) ? $orderDetails['ord_address2'] . "<br>" : ""; ?>
                                <?php echo (!empty($orderDetails['ord_city'])) ? $orderDetails['ord_city'] . ", " : ""; ?>
                                <?php echo (!empty($orderDetails['ord_province'])) ? $orderDetails['ord_province'] . ", " : ""; ?>
                                <?php echo (!empty($orderDetails['ord_postal_code'])) ? $orderDetails['ord_postal_code'] . "<br>" : ""; ?>
                            </strong>
                        </div>
                    </td>
                    <td style="vertical-align: top;">
                        <span style="color: #8c8f95; font-size: 14px;">SHIP TO</span>
                        <div style="color:#19000c">
                            <strong>
                                <?php echo (!empty($orderDetails['customer'])) ? $orderDetails['customer'] . "<br>" : ""; ?>
                                <?php echo (!empty($orderDetails['ord_address1'])) ? $orderDetails['ord_address1'] . "<br>" : ""; ?>
                                <?php echo (!empty($orderDetails['ord_address2'])) ? $orderDetails['ord_address2'] . "<br>" : ""; ?>
                                <?php echo (!empty($orderDetails['ord_city'])) ? $orderDetails['ord_city'] . ", " : ""; ?>
                                <?php echo (!empty($orderDetails['ord_province'])) ? $orderDetails['ord_province'] . ", " : ""; ?>
                                <?php echo (!empty($orderDetails['ord_postal_code'])) ? $orderDetails['ord_postal_code'] . "<br>" : ""; ?>
                            </strong>
                        </div>
                    </td>
                    <td style="vertical-align: top;">
                        <span style="color: #8c8f95; font-size: 14px;"> SHIP DATE </span>
                        <span style="color:#19000c; display: inline-block; margin-left: 30px;">
                            <?php echo (!empty($orderDetails['shipdate']) && $orderDetails['shipdate'] != "0000-00-00") ? date("m/d/Y", strtotime($orderDetails['shipdate'])) : ""; ?>
                        </span>
                    </td>
                    <td style="vertical-align: top;">
                        <div>
                            <span style=" display: inline-block;color: #8c8f95; font-size: 14px; width: 130px;"> INVOICE </span>
                            <span style="color:#19000c; display: inline-block;">
                                <?php echo $orderDetails['invoiceno']; ?>
                            </span>
                        </div>
                        <div>
                            <span style=" display: inline-block;color: #8c8f95; font-size: 14px; width: 130px;"> DATE </span>
                            <span style="color:#19000c; display: inline-block;">
                                <?php echo (!empty($orderDetails['invoicedate'])) ? date("m/d/Y", strtotime($orderDetails['invoicedate'])) : ""; ?>
                            </span>
                        </div>
                        <div>
                            <span style=" display: inline-block;color: #8c8f95; font-size: 14px; width: 130px;"> TERMS </span>
                            <span style="color:#19000c; display: inline-block;">
                                <?php echo $orderDetails['terms']; ?>
                            </span>
                        </div>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-bottom: 10px;">
            <table border="0" style="width: 100%; margin-top: 35px;">
                <thead>
                    <tr style="background-color:#ccd1d8 ;">
                        <th style="padding: 8px; text-align: left;">DESCRIPTION</th>
                        <th style="text-align: right; padding: 8px;">PIECES</th>
                        <th style="text-align: right; padding: 8px;">BOXES</th>
                        <th style="text-align: right; padding: 8px;">WEIGHT (kg)</th>
                        <th style="text-align: right; padding: 8px;">RATE ($/kg)</th>
                        <th style="text-align: right; padding: 8px;">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($orderItems)) {
                        $categotyItem = [];
                        $orderItemsArr = [];
                        while ($row = mysqli_fetch_assoc($orderItems)) {
                            $orderItemsArr[] = $row;
                        }
                        foreach ($orderItemsArr as $rkey => $row) {
                            $orderid = $row["orderid"];
                            $code = $row["code"];
                            $description = $row["description"];
                            $tw = $row["tw"];
                            $tq = $row["tq"];
                            $tp = $row["tp"];
                            $minw = $row['minw'];
                            $maxw = $row['maxw'];
                            $subTotal += $tw * $tp;
                            $total += $tw * $tp;
                            if (!in_array($row["cat_code"], $categotyItem)) {
                                $categotyItem[] = $row["cat_code"];
                                $itemTotalQty = $tq;
                                $itemTotalWeight = $tw;
                                $itemSubTotal = $tw * $tp;
                                $itemTotalBox = $row["totalBox"];
                            } else {
                                $itemTotalQty += $tq;
                                $itemTotalWeight += $tw;
                                $itemSubTotal += $tw * $tp;
                                $itemTotalBox += $row["totalBox"];
                            }
                            $totalQty += $tq;
                            $totalWeight += $tw;
                            $totalBox += $row["totalBox"];
                    ?>


                            <?php if ((isset($orderItemsArr[$rkey + 1]) && $row["cat_code"] != $orderItemsArr[$rkey + 1]["cat_code"]) || $rkey == count($orderItemsArr) - 1) { ?>
                                <tr>
                                    <td style="padding:8px 8px;"><?php echo $row["cat_des"]; ?></td>
                                    <td style="text-align: right; padding:8px 8px;"><?php echo $itemTotalQty; ?></td>
                                    <td style="text-align: right; padding:8px 8px;"><?php echo $itemTotalBox; ?></td>
                                    <td style="text-align: right; padding:8px 8px;"><?php echo $itemTotalWeight; ?></td>
                                    <td style="text-align: right; padding:8px 8px;">$<?php echo number_format($tp, 2); ?></td>
                                    <td style="text-align: right; padding:8px 8px;">$<?php echo number_format($itemSubTotal, 2); ?></td>
                                </tr>
                            <?php } ?>
                        <?php
                        }
                        ?>
                        <tr style="border-top: 2px dotted #b9bec4;  border-bottom: 2px dotted #b9bec4; ">
                            <td style="text-align: right; padding:8px 8px;"><strong>Totals:</strong></td>
                            <td style="text-align: right; padding:8px 8px;"><?php echo $totalQty; ?></td>
                            <td style="text-align: right; padding:8px 8px;"><?php echo $totalBox; ?></td>
                            <td style="text-align: right; padding:8px 8px;"><?php echo $totalWeight; ?></td>
                            <td style="text-align: right; padding:8px 8px;">&nbsp;</td>
                            <td style="text-align: right; padding:8px 8px;">$<?php echo number_format($subTotal, 2); ?></td>
                        </tr>
                    <?php
                    }
                    if (!empty($orderItems) && mysqli_num_rows($orderItems) == 0) { ?>
                        <span>Sorry no records exist in the database!</span>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-top: 10px;">
            <table id="invoice-sum-block" style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="color:#8f98b5; font-size: 10px; text-align: left; padding-top: 10px; vertical-align: top; width: 40%;">
                            We appreciate your business!
                        </td>
                        <td style="text-align: right; padding:8px 8px; width: 60%;">
                            <table style="width: 100%;">
                                <tr style="vertical-align: top; width: 100%;">
                                    <td style="padding-bottom: 10px; width: 50%; vertical-align: top; color: #8c8f95; font-size: 14px; text-align: left;">
                                        <input id="optionalOrderId" name="optionalOrderId" type="hidden" value="<?php echo $orderDetails["id"]; ?>" />
                                        <input id="optionalLabel" name="optionalLabel" style="width: 250px;" type="text" value="<?php echo $orderDetails["inv_label"]; ?>" placeholder="" />
                                    </td>
                                    <td style="padding-bottom: 10px;  width: 50%; vertical-align: top; color: #000; font-size: 14px; text-align: right;">
                                        $ <input id="optionalValue" name="optionalValue" style="width: 60px; text-align: right;" type="text" value="<?php echo (isset($orderDetails["inv_val"]) && !empty($orderDetails["inv_val"]) && $orderDetails["inv_val"] != 0.00) ? $orderDetails["inv_val"] : ""; ?>" placeholder="" />
                                    </td>
                                </tr>
                                <tr style="vertical-align: top; width: 100%;">
                                    <td style="padding-bottom: 10px; width: 50%; vertical-align: top; color: #8c8f95; font-size: 14px; text-align: left;">SUBTOTAL</td>
                                    <td style="padding-bottom: 10px;  width: 50%; vertical-align: top; color: #000; font-size: 14px; text-align: right;">$<?php echo number_format($subTotal + $orderDetails["inv_val"], 2); ?></td>
                                </tr>
                                <tr style="vertical-align: top; width: 100%;">
                                    <td style=" padding-bottom: 10px; width: 50%; vertical-align: top; color: #8c8f95; font-size: 14px; text-align: left;">GST @ 0</td>
                                    <td style="padding-bottom: 10px;  width: 50%; vertical-align: top; color: #000; font-size: 14px; text-align: right;">$0.00</td>
                                </tr>
                                <tr style="vertical-align: top; width: 100%;">
                                    <td style=" padding-bottom: 10px; width: 50%; vertical-align: top; color: #8c8f95; font-size: 14px; text-align: left;">TOTAL</td>
                                    <td style="padding-bottom: 10px;  width: 50%; vertical-align: top; color: #000; font-size: 14px; text-align: right;">$<?php echo number_format($total + $orderDetails["inv_val"], 2); ?></td>
                                </tr>
                                <tr style="vertical-align: top; width: 100%;">
                                    <td colspan="2" style="border-bottom: 2px dotted #b9bec4; padding-bottom: 10px;">
                                </tr>
                                <tr style="vertical-align: top; width: 100%;">
                                    <td style=" padding-top: 10px; width: 50%; vertical-align: top; color: #8c8f95; font-size: 14px; text-align: left;">BALANCE DUE</td>
                                    <td style="padding-top: 10px;  width: 50%; vertical-align: top; color: #000; font-size: 14px; text-align: right; font-weight: bold;">$<?php echo number_format($total + $orderDetails["inv_val"], 2); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>