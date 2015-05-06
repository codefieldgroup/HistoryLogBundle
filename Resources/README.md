

1) Installing the Standard Edition
----------------------------------
Add intro all entities that you need save fields modifications:


	/**
	 * Get Parameters History Log
	 *
	 * @return array
	 */
	public function getParametersHistoryLog() {
		return ['name', 'templateIndication', 'datetime_r' ,'status','description'];
	}


2) Checking your System Configuration
-------------------------------------
