<?php
class Welcome extends Trongate {

	/**
	 * Renders the (default) homepage for public access.
	 *
	 * @return void
	 */
	public function index(): void {
        $data['view_module'] = 'welcome';
        $data['view_file'] = 'welcome';
        $data['additional_includes_top'] = [
			'welcome_module/css/welcome.css'
		];

		$this->module('localization');
		$this->localization->_load_language(
			$this->localization->_get_language_from_header()
		);

		$translations = [
			'da_DK' => 
		];

        $this->template('public', $data);
	}

}
