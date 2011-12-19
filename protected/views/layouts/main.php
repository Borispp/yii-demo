<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.1)" />
<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.1)" />
<script type="text/javascript">
	var _base_url = '<?php echo Yii::app()->getBaseUrl(true)?>';
	var _member_url = '<?php echo Yii::app()->getBaseUrl(true)?>/member';
</script>
<title><?php echo CHtml::encode($this->getMetaTitle()); ?></title>
<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
    <div id="header-wrapper">
        <header class="w cf">
            <h1><a href="<?php echo Yii::app()->homeUrl; ?>">YourStudioApp</a></h1>
            <section class="login">
                <?php if (Yii::app()->user->isGuest) : ?>
                    <?php echo YsaHtml::link('Login', array('/login')); ?>
                <?php else:?>
                    <?php echo YsaHtml::link('Logout', array('/logout')); ?>
                <?php endif; ?>
            </section>
            <?php if ($this->isWebsite()) : ?>
                <section class="social">
                    <a href="" class="fb">Facebook</a>
                    <span></span>
                    <a href="" class="twi">Twitter</a>
                </section>
            <?php endif; ?>
            <nav>
                <?php $this->widget('zii.widgets.CMenu',array(
                    'htmlOptions' => array(
                        'class' => 'cf'
                    ),
                    'items'=> $this->getWebsiteNavigationMenu()
                )); ?>
            </nav>
        </header>
    </div>
    <section id="content">
        <?php echo $content; ?>
    </section>
    
    <div class="lighter-w footer-w">
        <footer class="w">
            <p><?php echo Yii::app()->settings->get('copyright'); ?>&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;site by <a href="http://flosites.com" rel="external">Flosites</a></p>
        </footer>
    </div>
</body>
</html>