<?php

/**
 * Class Repeater
 */
class Repeater extends GenerateMeta {

	public function __construct($id, $title, $screen = null, $callback_args = null)
	{
		$this->screen = $screen;
		$this->callback_arg = $callback_args;
		parent::__construct($id, $title, $this->screen, $this->callback_arg);
	}

	public function metaInvokeCallback( $post )
	{
		$value         = null;
		$out           = "";
		$template      = "";
		$sections = null;
		$loop_type = "single";

		if ( get_post_meta( $post->ID, $this->id, true ) ) {
			$value = get_post_meta( $post->ID, $this->id, true );
			$sections = unserialize($value);
			$loop_type = "multiple";
		}

		?>

		<div class='GenerateMeta generate-repeater-metabox' data-post-id="<?php echo $post->ID ?>">
			<textarea id="<?php echo $this->id ?>-identifier" name="<?php echo $this->id ?>" data-handler="data" class="hidden-meta-repeater-container"><?php echo $value ?></textarea>
			<div class="data-page-repeater">
				<?php

				// Loop for template
				$section_head = "<div data-section-head=''></div>";
				$template .="<section data-unique='' data-sort=''>{$section_head}";
				$template .="<input type='text' data-section-name='section-name' placeholder='Enter Name of your section'>";
				foreach ( $this->options as $single_field ) {
					switch($single_field['type']) {

						case "textarea" :
							$template .= "<p>{$single_field['name']}</p>";
							$template .="<textarea class='input-class-{$single_field['name']}' data-type='textarea' data-input='{$single_field['name']}'></textarea>";
							break;

						case "image" :
							$template .= "<div class='image-repeater-field'><div class='image-holder'></div>";
							$template .="<p>{$single_field['name']}</p>";
							$template .="<input value='' class='input-class-{$single_field['name']}' type='text' data-type='image' data-input='{$single_field['name']}'><a class='button-secondary' data-image-trigger>Upload</a>";
							$template .="</div>";
							break;

						case "flex-field" :
							$template .="<p>{$single_field['name']}</p>";
							$template .="<input data-flex='{$single_field['type']}' value='' class='input-class-{$single_field['name']}' type='text' data-type='text' data-input='{$single_field['name']}'>";
							break;

						default :
							$template .="<p>{$single_field['name']}</p>";
							$template .="<input value='' class='input-class-{$single_field['name']}' type='text' data-type='text' data-input='{$single_field['name']}'>";
					}
				}
				$template .="<button data-unique-button='' data-stored-template='btn-destroy' data-destroy='destroy' class='button button-secondary'><i class='fa fa-remove'></i></button></section>";
				$template.="</section>";


				// Loop for non existing data
				if($loop_type === 'single' || $sections === 'empty_data' ) {
					$out .="<section class='collapsed-section' data-unique='' data-sort='1'>$section_head";
					$out .="<input type='text' data-section-name='section-name' placeholder='Enter Name of your section'>";
					foreach ( $this->options as $single_field ) {

						switch($single_field['type']) {
							case "textarea" :
								$out .= "<p>{$single_field['name']}</p>";
								$out .="<textarea class='input-class-{$single_field['name']}' data-type='textarea' data-input='{$single_field['name']}'></textarea>";
								break;

							case "image" :
								$out .= "<div class='image-repeater-field'><div class='image-holder'></div>";
								$out .="<p>{$single_field['name']}</p>";
								$out .="<input value='' class='input-class-{$single_field['name']}' type='text' data-type='image' data-input='{$single_field['name']}'><a class='button-secondary' data-image-trigger>Upload</a>";
								$out .="</div>";
								break;

							case "flex-field" :
								$out .= "<p>{$single_field['name']}</p>";
								$out .="<input data-flex='{$single_field['type']}' value='' class='input-class-{$single_field['name']}' type='text' data-type='text' data-input='{$single_field['name']}'>";
								break;
							default :
								$out .= "<p>{$single_field['name']}</p>";
								$out .="<input value='' class='input-class-{$single_field['name']}' type='text' data-type='text' data-input='{$single_field['name']}'>";
						}
					}
					$out .="<button data-unique-button='' data-destroy='destroy' class='button button-secondary'><i class='fa fa-remove'></i></button></section>";
					$out .="</section>";
				}


				// Loop for Existing data
				else {

					foreach ( $sections as $sk => $sv ) {
						$out .="<section class='collapsed-section' data-unique='{$sk}' data-sort='1'>$section_head";
						$out .="<input type='text' value='{$sv['sectionName']}' data-section-name='section-name' placeholder='Name of your section'>";
						foreach ( $sv as $fldsk => $fldsv ) {
							if($fldsk === 'sectionName')
								continue;

							switch($fldsv['field_type']) {
								case "textarea" :
									$out .= "<p>{$fldsk}</p>";
									$out .="<textarea class='input-class-{$fldsk}' data-type='textarea' data-input='{$fldsk}'>{$fldsv['value']}</textarea>";
									break;

								case "image" :
									$image_holders = null;
									foreach(explode(',', $fldsv['value']) as $image) {
										$image_holders .= "<div class='image-repeater-wrap'><img class='repeater-image' src='{$image}'></div>";
									}
									$out .= "<div class='image-repeater-field'><div class='image-holder'>{$image_holders}</div>";
									$out .="<p>{$fldsk}</p>";
									$out .="<input value='{$fldsv['value']}' class='input-class-{$fldsk}' type='text' data-type='image' data-input='{$fldsk}'><a class='button-secondary' data-image-trigger>Upload</a>";
									$out .="</div>";
									break;

								case "flex-field" :
									$out .= "<p>{$fldsk}</p>";
									$out .="<input data-flex='{$fldsk}' value='{$fldsv['value']}' class='input-class-{$fldsk}' type='text' data-type='{$fldsk}' data-input='{$fldsk}'>";
									break;
								default :
									$out .= "<p>{$fldsk}</p>";
									$out .="<input value='{$fldsv['value']}' class='input-class-{$fldsk}' type='text' data-type='text' data-input='{$fldsk}'>";
							}

						}

						$out .="<button data-unique-button='{$sk}' data-destroy='destroy' class='button button-secondary'><i class='fa fa-remove'></i></button></section>";
						$out .="</section>";
					}
				}


				echo $out;
				?>
			</div>
			<div class="create-section-repeater">
				<button class="button button-secondary" data-add-section="add">Add section</button>
			</div>
			<input type="hidden" data-template="<?php echo $template ?>"/>
			<hr>
			<p><button data-repeater-submit="subm" class="button-primary">Save Repeater</button></p>
			<p><small><?php echo $this->id ?></small></p>
		</div>
		<?php
	}
}