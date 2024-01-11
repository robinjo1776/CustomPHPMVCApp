<?php
date_default_timezone_set('America/Edmonton');
include "dbConfig.php";

$terms_service_menu_active = "active";
$pageTitle = "Terms & Service";
?>
<?php require_once(VIEW_PATH . 'common/header.php'); ?>

<body>
    <div class="container-fluid-lg">
        <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
        <div class="inner-banner-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1><?php echo $pageTitle; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4>1. Terms</h4>
                        <p>By accessing this Website, accessible from www.wintersturkeys.ca, you are agreeing to be bound by these Website Terms and Conditions of Use and agree that you are responsible for the agreement with any applicable local laws. If you disagree with any of these terms, you are prohibited from accessing this site. The materials contained in this Website are protected by copyright and trade mark law.</p>
                        <br>
                        <h4>2. Use License</h4>
                        <p>Permission is granted to temporarily download one copy of the materials on Winter's Turkey's Website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                        <ul>
                            <li>modify or copy the materials;</li>
                            <li>use the materials for any commercial purpose or for any public display;</li>
                            <li>attempt to reverse engineer any software contained on Winter's Turkey's Website;</li>
                            <li>remove any copyright or other proprietary notations from the materials; or </li>
                            <li>transferring the materials to another person or "mirror" the materials on any other server. This will let Winter's Turkeys to terminate upon violations of any of these restrictions. Upon termination, your viewing right will also be terminated and you should destroy any downloaded materials in your possession whether it is printed or electronic format. These Terms of Service has been created with the help of the Terms Of Service Generator.</li>
                        </ul>
                        <br>
                        <h4>3. Disclaimer</h4>
                        <p>All the materials on Winter's Turkey's Website are provided "as is". Winter's Turkeys makes no warranties, may it be expressed or implied, therefore negates all other warranties. Furthermore, Winter's Turkeys does not make any representations concerning the accuracy or reliability of the use of the materials on its Website or otherwise relating to such materials or any sites linked to this Website.</p>
                        <br>
                        <h4>4. Limitations</h4>
                        <p>Winter's Turkeys or its suppliers will not be hold accountable for any damages that will arise with the use or inability to use the materials on Winter's Turkey's Website, even if Winter's Turkeys or an authorize representative of this Website has been notified, orally or written, of the possibility of such damage. Some jurisdiction does not allow limitations on implied warranties or limitations of liability for incidental damages, these limitations may not apply to you.</p>
                        <br>
                        <h4>5. Revisions and Errata</h4>
                        <p>The materials appearing on Winter's Turkey's Website may include technical, typographical, or photographic errors. Winter's Turkeys will not promise that any of the materials in this Website are accurate, complete, or current. Winter's Turkeys may change the materials contained on its Website at any time without notice. Winter's Turkeys does not make any commitment to update the materials.</p>
                        <br>
                        <h4>6. Links</h4>
                        <p>Winter's Turkeys has not reviewed all of the sites linked to its Website and is not responsible for the contents of any such linked site. The presence of any link does not imply endorsement by Winter's Turkeys of the site. The use of any linked website is at the user's own risk.</p>
                        <br>
                        <h4>7. Site Terms of Use Modifications</h4>
                        <p>Winter's Turkeys may revise these Terms of Use for its Website at any time without prior notice. By using this Website, you are agreeing to be bound by the current version of these Terms and Conditions of Use.</p>
                        <br>
                        <h4>8. Your Privacy</h4>
                        <p>Please read our <a href="<?php echo Helper::fullbaseUrl(); ?>privacy-policy.php">Privacy Policy.</a></p>
                        <br>
                        <h4>9. Governing Law</h4>
                        <p>Any claim related to Winter's Turkey's Website shall be governed by the laws of ca without regards to its conflict of law provisions</p>
                        <br> <br>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once(VIEW_PATH . 'common/footer.php'); ?>
    </div>
</body>

</html>