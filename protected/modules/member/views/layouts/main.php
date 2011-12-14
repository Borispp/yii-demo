<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.1)" />
<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.1)" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<title><?php echo CHtml::encode($this->getMetaTitle()); ?></title>
<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
    <div id="mainmenu">
        <?php $this->widget('zii.widgets.CMenu',array(
            'items'=>array(
                array('label'=>'Home', 'url'=>array('/')),
                array('label'=>'My Application', 'url'=>array('application/')),
                array('label'=>'Events', 'url'=>array('event/')),
                array('label'=>'Login', 'url'=>array('/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Settings', 'url'=>array('settings/'), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/logout'), 'visible'=>!Yii::app()->user->isGuest)
            ),
        )); ?>
    </div><!-- mainmenu -->
    
    <?php if(Yii::app()->user->hasFlash('success')): ?>
        <div class="flash success">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php elseif (Yii::app()->user->hasFlash('notice')): ?>
        <div class="flash notice">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php elseif (Yii::app()->user->hasFlash('error')): ?>
        <div class="flash error">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif;?>
    
    <?php echo $content; ?>
</body>
</html>