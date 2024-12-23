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

		$this->module('localizations');
		$data['t'] = $this->localizations->_translator(
			get_language()
		);

    $this->template('public', $data);
	}

}
