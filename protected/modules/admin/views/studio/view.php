<div class="g12">
	<form>
		<fieldset>
			<label>General Information</label>
			<section>
				<label>Name</label>
				<div><?php echo $entry->name?></div>
			</section>
			<section>
				<label>Owner</label>
				<div><a href="<?php echo Yii::app()->createUrl('/admin/member/view/', array(
						'id'	=> $entry->user->id
				))?>"><?php echo $entry->user->name()?></a></div>
			</section>
			<section>
				<label>Splash screen code</label>
				<div><?php echo $entry->splash?></div>
			</section>
			<section>
				<label>Specials</label>
				<div id="specials-url"><?php echo $entry->specials()?></div>
			</section>
			<section>
				<label>Created</label>
				<div><?php echo date('m.d.Y H:i', strtotime($entry->created))?></a></div>
			</section>
			<section>
				<label>Updated</label>
				<div><?php echo date('m.d.Y H:i', strtotime($entry->updated))?></a></div>
			</section>
		</fieldset>
		<fieldset>
			<label>Feeds</label>
			<section>
				<label>Facebook Feed</label>
				<div><a href="<?php echo $entry->facebook_feed?>"><?php echo $entry->facebook_feed?></a></div>
			</section>
			<section>
				<label>Twitter Feed</label>
				<div><a href="<?php echo $entry->twitter_feed?>"><?php echo $entry->twitter_feed?></a></div>
			</section>
		</fieldset>
		<fieldset>
			<label>Links</label>
			<?php foreach($links as $obLink):?>
			<section>
				<label>
					<?php echo $obLink->name?>
				</label>
				<div>
					<a href="<?php echo $obLink->url?>"><?php echo $obLink->url?></a><br/>
					Friendly: <a href="<?php echo $obLink->friendly_url?>"><?php echo $obLink->friendly_url?></a>
				</div>
			</section>
			<?php endforeach?>
		</fieldset>
		<fieldset>
			<label>Persons</label>
			<?php foreach($persons as $obPerson):?>
			<section>
				<label>
					<?php echo $obPerson->name?><br>
				</label>
				<div>
					<?php echo $obPerson->photo()?><br/>
					<?php echo nl2br($obPerson->description)?>
				</div>
			</section>
			<?php endforeach?>
		</fieldset>
		<? /*php foreach($options as $section => $properties):?>
		<fieldset>
			<label><?php echo $section?></label>
			<?php foreach($properties as $label => $value):?>
			<section>
				<label><?php echo $label?></label>
				<div><?php echo $value?></div>
			</section>
			<?endforeach?>
		</fieldset>
		<?endforeach*/?>
	</form>
</div>