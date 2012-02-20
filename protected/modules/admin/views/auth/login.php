<!doctype html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<title><?php echo YsaHtml::encode($this->pageTitle); ?></title>
	<meta name="robots" content="noindex">
	<!-- Google Font and style definitions -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans:regular,bold">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/style.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/light/theme.css" id="themestyle" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/advanced.css">
	<!--[if lt IE 9]>
		<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/adm/css/ie.css" />
	<![endif]-->
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1" />
</head>
<body id="login">
	<header>
		<div id="logo">
			<?php echo YsaHtml::link('YourStudioApp', array('/admin')); ?>
		</div>
	</header>
	<section id="content">
		<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'loginform',
		)); ?>
			<fieldset>
				<section>
					<?php echo $form->label($login,'email'); ?>
					<?php echo $form->textField($login,'email', array('autofocus' => 'autofocus')); ?>
				</section>
				<section>
					<?php echo $form->label($login,'password'); ?>
					<?php echo $form->passwordField($login,'password', array()); ?>
				</section>
				<section>
					<div><button class="fr submit">Login</button></div>
				</section>
			</fieldset>
		<?php $this->endWidget(); ?>
	</section>
	<footer>&copy; yourstudioapp.com <?php echo date('Y'); ?></footer>
</body>
</html>