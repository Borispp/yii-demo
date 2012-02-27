<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.1)" />
<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.1)" />
<title><?php echo YsaHtml::encode($this->getMetaTitle()); ?></title>
<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
    <div id="share-photo-header-wrapper">
        <header>
            <h1><a href="<?php echo Yii::app()->homeUrl; ?>">YourStudioApp</a></h1>
        </header>
    </div>
    <section id="content" class="cf">
        <?php echo $content; ?>
    </section>
</body>
</html>