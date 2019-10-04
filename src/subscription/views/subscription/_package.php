<?php
use yii\helpers\Url;
use common\helpers\Currency;
?>

<div class="row">
	<div class="col-md-4 mb-30">
		<article class="bg-white text-center pos-rel">
			<header class="bg-primary pos-rel px-20 py-40 text-center">
				<?php /*
				<svg class="pos-abs bottom-0 left-0 right-0" version="1.1" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="70px" viewBox="0 0 300 70">
				  <path d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729
				c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" opacity="0.6" fill="#ffffff" />
				  <path d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729
				c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" opacity="0.6" fill="#ffffff" />
				  <path d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716
				H42.401L43.415,98.342z" opacity="0.7" fill="#ffffff" />
				  <path d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428
				c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#ffffff" />
				</svg>
				*/ ?>
				<strong class="d-block color-white font-size-50 line-height-0_7 mb-20">
					<span class="valign-top font-size-default">RM</span><?= Currency::getIntegerPart($package->price) ?>
					<?php if (Currency::getDecimalPart($package->price) != '00'): ?>
						<span class="font-size-default">.<?= Currency::getDecimalPart($package->price) ?></span>
					<?php endif ?>
					<span class="font-size-default">/ month</span>
				</strong>
				<h3 class="h6 text-uppercase color-white-opacity-0_7 letter-spacing-3 mb-20"><?= $package->name ?></h3>
			</header>
		</article>
	</div>
	<div class="col-md-4 mb-30">
		<div class="px-20 py-40">
			<ul class="list-unstyled mb-40">
				<?php foreach ($package->packageItems as $item): ?>
					<li class="mb-20"><?= $item->toString() ?></li>
				<?php endforeach ?>
				<li class="mb-20">Online Support</li>
			</ul>
		</div>
	</div>
	<?php if ($actionButtonLabel !== false): ?>
		<div class="col-md-4 mb-30">
			<div class="px-20 py-40">
				<a class="btn btn-primary mb-10" href="<?= Url::to(['/subscription/subscription/create', 'package' => $package->id]) ?>">
					<?= $actionButtonLabel ?>
				</a>
			</div>
		</div>
	<?php endif ?>
</div>