<style>
    @media print {
        #footer {
            display: none;
        }
    }
</style>
<div id="shippingLabelBlock" class="col-md-12">
    <table style="
            width: 4in;
            height: 6in;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            border-top: 1px solid #4472c4;
            border-right: 1px solid #4472c4;
            border-bottom: 1px solid #4472c4;
            border-left: 1px solid #4472c4;
            vertical-align: top;
            margin-bottom: 100px;
        ">
        <tr>
            <td style="
                text-align: center;
                font-weight: bold;
                padding: 20px;
                border-top: 1px solid #4472c4;
                border-right: 1px solid #4472c4;
                border-bottom: 1px solid #4472c4;
                border-left: 1px solid #4472c4;   
                ">
                <div class="" style="
                 text-align: center;
                font-weight: bold;
                font-size: 51px;
                ">
                    <?php echo (!empty($orderDetails['customer'])) ? $orderDetails['customer'] : ""; ?>
                </div>

            </td>
        </tr>
        <tr>
            <td style="
                border-top: 1px solid #4472c4;
                border-right: 1px solid #4472c4;
                border-bottom: 1px solid #4472c4;
                border-left: 1px solid #4472c4;                
                padding: 15px;
                ">
                <table style="width: 4in;">
                    <tr>
                        <td style="
                    width: 	100%;
                    margin-left: auto;
                    margin-right: auto;
                    text-align: center;
                    font-size: 35px;
                    ">
                            /<?php echo  $sumBoxData["totalBox"]; ?> Boxes
                        </td>
                    </tr>
                    <tr>
                        <td style="
                    width: 	100%;
                    text-align: center;
                    font-size: 35px;
                    ">
                            / Pallet(s)
                        </td>
                    </tr>
                    <tr>
                        <td style="
                    width: 	100%;
                    text-align: center;
                    font-size: 35px;
                    ">
                            <?php
                            $dateString = (!empty($orderDetails['shipdate']) && $orderDetails['shipdate'] != "0000-00-00") ? date("Y-m-d", strtotime($orderDetails['shipdate'])) : "";

                            if (!empty($dateString)) {
                                // Convert the date string to a DateTime object
                                $date = new DateTime($dateString);
                                // Get the month and day
                                $month = $date->format("M"); // "M" format gives the abbreviated month name
                                $day = $date->format("j");  // "j" format gives the day of the month without leading zeros

                                // Determine the suffix for the day (e.g., "st," "nd," "rd," or "th")
                                if ($day % 10 == 1 && $day != 11) {
                                    $suffix = "st";
                                } elseif ($day % 10 == 2 && $day != 12) {
                                    $suffix = "nd";
                                } elseif ($day % 10 == 3 && $day != 13) {
                                    $suffix = "rd";
                                } else {
                                    $suffix = "th";
                                }

                                // Combine the components to get the desired format
                                $formattedDate = $month . " " . $day . $suffix;
                                echo $formattedDate;
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="
                border-top: 1px solid #4472c4;
                border-right: 1px solid #4472c4;
                border-bottom: 1px solid #4472c4;
                border-left: 1px solid #4472c4;
                text-align: center;
                font-weight: bold;
                font-size: 53px;
                padding: 15px;
                position: relative;
                ">
                <?php echo $orderDetails["shipvia"]; ?>
                <img style="    
            margin-bottom: 5px;
            border-collapse: collapse;
            position: absolute;
            width: 57px;
            bottom: 0;
            right: 5px;" src="../../images/invoice-logo.png" alt="logo">
            </td>
        </tr>
    </table>
</div>