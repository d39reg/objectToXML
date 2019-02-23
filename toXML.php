<?php
  function toXML($object)
	{
	
		function _aXML($memory, $object)
		{
			foreach ($object as $attr => $data)
			{
				if ($data) xmlwriter_write_attribute ($memory, $attr, $data);
			}
		}
	
		function _oXML($memory, $object, $backTag = 'tag')
		{
			if (gettype($object) == 'string')
			{
				xmlwriter_text($memory, $object);
				return;
			}
			foreach ($object as $tag => $data)
			{
				if (gettype($tag) == 'integer') $tag = $backTag;
				xmlwriter_start_element ($memory, $tag);
				switch (gettype($data))
				{
					case 'string':
					case 'integer':
						xmlwriter_text($memory, $data);
					break;
					case 'array':
						if (array_key_exists('attr', $data))
						{
							_aXML($memory, $data['attr']);
							_oXML($memory, $data['inner'], $tag);
						}
						else _oXML($memory, $data, $tag);
					break;
				}
				xmlwriter_end_element ($memory);
			}
		}
		
		$memory = xmlwriter_open_memory();
		xmlwriter_start_document($memory,'1.0','UTF-8'); // <?xml version="1.0" encoding="UTF-8" ? >
		_oXML($memory, $object);
		xmlwriter_end_dtd($memory);
		return xmlwriter_output_memory($memory, true);

	}
	
	$data = [
		'offers'=>[
			'offer'=>[
				'build-name'=>'Name'
				,'korps'=>[
					'korp'=>[
						'num'=>'1'
						,'rooms'=>[
							'room'=>[
								[
									'attr'=>[
										'offer_id'=>123
									]
									,'inner'=>[
										'id'=>1
										,'link'=>1
										,'num'=>1
										,'floor'=>1
										,'square'=>1
										,'price'=>1
										,'square_price'=>1
										,'image'=>[
											'attr'=>[
												'tag'=>'plan'
											]
											,'inner'=>'htp://'
										]
									]
								]
								
								,[
									'attr'=>[
										'offer_id'=>123
									]
									,'inner'=>[
										'id'=>1
										,'link'=>1
										,'num'=>1
										,'floor'=>1
										,'square'=>1
										,'price'=>1
										,'square_price'=>1
										,'image'=>[
											'attr'=>[
												'tag'=>'plan'
											]
											,'inner'=>'htp://'
										]
									]
								]
								
							]
						]
					]
				]
				,'images'=>[
				
					'image'=>[
						[
							'attr'=>[
								'tag'=>'genplan'
							]
							,'inner'=>'htp://'
						]
						,[
							'attr'=>[
								'tag'=>'dynamics'
								,'month'=>'8'
								,'year'=>'2018'
							]
							,'inner'=>'htp://'
						]
					]
				]
			]
		]
	];
	echo toXML($data);
