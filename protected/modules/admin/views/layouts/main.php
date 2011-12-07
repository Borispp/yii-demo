<!doctype html>
<html lang="en-us">
<head>
    <meta charset="utf-8">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta name="description" content="" />
    
    <script type="text/javascript">
        var _admin_url = '<?php echo CController::createUrl('/admin')?>';
    </script>

    <!-- Google Font and style definitions -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:regular,bold">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/style.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/light/theme.css" id="themestyle" />    
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/advanced.css">
    
    <!-- editor -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/elrte/css/smoothness/jquery-ui-1.8.13.custom.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/elrte/css/elrte.min.css">
    
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/ie.css" />
    <![endif]-->

    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1" />

    <!-- Use Google CDN for jQuery and jQuery UI -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>

    <!-- Loading JS Files this way is not recommended! Merge them but keep their order -->

    <!-- some basic functions -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/functions.js"></script>

    <!-- all Third Party Plugins and Whitelabel Plugins -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/plugins.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/editor.js"></script>
    
    
    
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/calendar.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/flot.js"></script>
    
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/elrte/elrte.min.js"></script>
    
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/elfinder.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/datatables.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Alert.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Autocomplete.js"></script>
    
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Calendar.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Chart.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Color.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Date.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Editor.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_File.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Dialog.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Fileexplorer.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Form.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Multiselect.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Number.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Password.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Slider.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Store.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Time.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Valid.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/wl_Widget.js"></script>
    
    <!-- configuration to overwrite settings -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/config.js"></script>

    <!-- the script which handles all the access to plugins etc... -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/script.js"></script>
    
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/adm/js/advanced.js"></script>
</head>
<body>
    <div id="pageoptions">
        <ul>
            <li><?php echo CHtml::link('Logout', array('/logout'))?></li>
            <li><?php echo CHtml::link('Configuration', '#', array('id' => 'wl_config'))?></li>
            <li><?php echo CHtml::link('Website', Yii::app()->homeUrl)?></li>
        </ul>
        <div>
            <h3>Place for some configs</h3>
            <p>&nbsp;</p>
        </div>
    </div>
    
    <header>
        <div id="logo">
            <?php echo CHtml::link('YourStudioApp', array('/admin')); ?>    
        </div>
        <div id="header">
            <?/*
                <ul id="headernav">
                        <li><ul>
                                <li><a href="icons.html">Icons</a><span>300+</span></li>
                                <li><a href="#">Submenu</a><span>4</span>
                                        <ul>
                                                <li><a href="#">Just</a></li>
                                                <li><a href="#">another</a></li>
                                                <li><a href="#">Dropdown</a></li>
                                                <li><a href="#">Menu</a></li>
                                        </ul>
                                </li>
                                <li><a href="login.html">Login</a></li>
                                <li><a href="wizard.html">Wizard</a><span>Bonus</span></li>
                                <li><a href="#">Errorpage</a><span>new</span>
                                        <ul>
                                                <li><a href="error-403.html">403</a></li>
                                                <li><a href="error-404.html">404</a></li>
                                                <li><a href="error-405.html">405</a></li>
                                                <li><a href="error-500.html">500</a></li>
                                                <li><a href="error-503.html">503</a></li>
                                        </ul>
                                </li>
                        </ul></li>
                </ul>
             
                <div id="searchbox">
                        <form id="searchform">
                                <input type="search" name="query" id="search" placeholder="Search">
                        </form>
                </div>
                */?>
        </div>
    </header>
    
<!--    'active'=>Yii::app()->controller->id=='news'-->
    <nav>
        <?php 
            $settingsItems = OptionGroup::model()->getNavigationList();
            $settingsItems[] = array(
                'label' => 'Option Groups', 
                'url' => array('/admin/optionGroup/'),
                'linkOptions' => array('class' => $this->getNavigationClass('optionGroup')), 
            );
            
            $this->widget('YsaAdminMenu',array(
                'id'    => 'nav',
                'items'=>array(
                        array(
                            'label'=>'Dashboard', 
                            'url'=>array('/admin'),
                            'itemOptions' => array('class' => 'i_house'), 
                            'linkOptions' => array('class' => $this->getNavigationClass('default')), 
                        ),
                        array(
                            'label' =>'Settings', 
                            'url'   => '',
                            'active' => $this->getNavigationClass('settings'),
                            'linkOptions' => array('class' => $this->getNavigationClass('settings,optionGroup')), 
                            'items' => $settingsItems,
                        ),
                        array(
                            'label'=>'Administrators', 
                            'url'=>array('/admin/administrator'),
                            'linkOptions' => array('class' => $this->getNavigationClass('administrator')), 
                        ),
                        array(
                            'label' =>'Members', 
                            'url' => array('/admin/member/'),
                            'linkOptions' => array('class' => $this->getNavigationClass('member')), 
                            'active' => $this->getNavigationClass('member'),
                        ),
                        array(
                            'label'=>'Applications', 
                            'url'=>array('/admin/application'),
                            'linkOptions' => array('class' => $this->getNavigationClass('application')), 
                        ),
                        array(
                            'label'=>'Payments', 
                            'url'=>array('/admin/payment'),
                            'linkOptions' => array('class' => $this->getNavigationClass('payment')), 
                        ),
                        array(
                            'label'=>'Pages', 
                            'url'=>array('/admin/page'),
                            'linkOptions' => array('class' => $this->getNavigationClass('page')), 
                        ),
                        array(
                            'label'=>'Newsletters', 
                            'url'=>array('/admin/email'),
                            'linkOptions' => array('class' => $this->getNavigationClass('email')), 
                        ),
                ),
        )); ?>
    </nav>

    <section id="content">
        <div class="g12">
            <?php if ($this->hasContentTitle()) : ?>
                <h1 class="content-title"><?php echo $this->getContentTitle(); ?></h1>
            <?php endif; ?>
            <?php if ($this->hasContentDescription()) : ?>
                <p class="content-description"><?php echo $this->getContentDescription(); ?></p>
            <?php endif; ?>
        </div>
        
        
        
        <?php echo $content; ?>
    </section>
    <footer>&copy; yourstudioapp.com <?php echo date('Y'); ?></footer>
    
<?/*
<div class="container" id="page">

	<div id="header">
		<div id="logo">Admin Area | <?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

</div><!-- page -->
*/?>
</body>
</html>