<?php

	function toXML($object)
	{
		global $__X__;
		$__X__ = xmlwriter_open_memory();
		xmlwriter_start_document($__X__,'1.0','UTF-8');
		function isAssoc($array)
		{
			$keys = array_keys($array);
			return array_keys($keys) !== $keys;
		}
		function convert($o, $bTag = null)
		{
			global $__X__;
			switch (gettype($o))
			{
				case 'array':
				
					foreach ($o as $attr => $data)
					{					
						switch (gettype($attr))
						{
							case 'string':
								if ($attr[0] == '#')
								{
									switch ($attr)
									{
										case '#data':
											if ($data) convert($data, $bTag);
										break;
										case '#attr':
											if ($data) 
											{
												if (gettype($data)=='array')
												{
													foreach ($data as $name=>$value)
													{
														if ($value) xmlwriter_write_attribute ($__X__, $name, $value);
													} 
												}
											}
										break;
									}
								}
								else
								{
									switch (gettype($data))
									{
										case 'string':
										case 'integer':
										case 'double':
											if ($data)
											{
												xmlwriter_start_element($__X__, $attr);
												xmlwriter_text($__X__, $data);
												xmlwriter_end_element($__X__);
											}
										break;
										case 'array':
											
											if (isAssoc($data))
											{
												@xmlwriter_start_element($__X__, $attr);
												convert($data, $attr);
												xmlwriter_end_element($__X__);
											}
											else convert($data, $attr);
											
										break;
									}
								}
							break;
							case 'integer':
								if ($bTag != null)
								{
									xmlwriter_start_element($__X__, $bTag);
									convert($data, $bTag);
									xmlwriter_end_element($__X__);
								}
							break;
						}
					}
				break;
				case 'string':
				case 'integer':
				case 'double':
					if ($o) xmlwriter_text($__X__, $o);
				break;
			}
		}
		xmlwriter_end_dtd($__X__);
		convert($object);
		return xmlwriter_output_memory($__X__, true);
	}
	
	echo toXML([
		'window'=>[
			[
				'#attr'=>[
					'x'=>100,
					'y'=>150
				],
				"button"=>[
					'#attr'=>[
						'x'=>100,
						'y'=>150
					],
					'#data'=>'Button 123'
				],
				"label"=>"test"
			],
			[
				'#attr'=>[
					'x'=>200,
					'y'=>250
				]
			]
		]
	]);
