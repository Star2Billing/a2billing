<?php
	
	//common 

	//XMLEntities
	$g_XMLEntities = array();
	$g_XMLEntities[] = new XMLEntity(0x26, "&amp;");
	$g_XMLEntities[] = new XMLEntity(0x22, "&quot;");
	$g_XMLEntities[] = new XMLEntity(0x27, "&apos;");
	$g_XMLEntities[] = new XMLEntity(0x3c, "&lt;");
	$g_XMLEntities[] = new XMLEntity(0x3e, "&gt;");

	abstract class Nullable
	{
		protected $m_boHasValue;
		
		function getHasValue()
		{
			return $this->m_boHasValue;
		}
		
		public function __construct()
		{
			$this->m_boHasValue = false;	
		}
	}

	class NullableInt extends Nullable 
	{
		private $m_nValue;
		
		function getValue()
		{
			if ($this->m_boHasValue == false)
			{
				throw new Exception('Object has no value');
			}
			
			return $this->m_nValue;
		}
		function setValue($value)
		{
			$this->m_boHasValue = true;
			$this->m_nValue = $value;
		}
		
		//constructor
		public function __construct($nValue)
		{
			Nullable::__construct();
			
			$this->setValue($nValue);
		}
	}

	class NullableBool extends Nullable 
	{
		private $m_boValue;
		
		public function getValue()
		{
			if ($this->m_boHasValue == false)
			{
				throw new Exception("Object has no value");
			}
			
			return ($this->m_boValue); 
		}
		public  function setValue($value)
		{
			$this->m_boHasValue = true;
			$this->m_boValue = $value;
		}
		
		//constructor
		public function __construct($boValue)
		{
			Nullable::__construct();
			
			$this->setValue($boValue);
		}
	}

	/******************/
	/* Common classes */
	/******************/
	class StringList
	{
		private $m_lszStrings;
		
		public function getAt($nIndex)
		{
			if ($nIndex < 0 ||
				$nIndex >= count($this->m_lszStrings))
				{
					throw new Exception('Array index out of bounds');
				}
				
				return (string)($this->m_lszStrings[$nIndex]);
		}
		
		function getCount()
		{
			return count($this->m_lszStrings);
		}
		
		function add($szString)
		{
			if (!is_string($szString))
			{
				throw new Exception('Invalid parameter type');
			}
		
			return ($this->m_lszStrings[] = $szString);
		}
		
		//constructor
		function __construct()
		{
			$this->m_lszStrings = array();
		}
	}

	class ISOCountry
	{
		private $m_szCountryName;
		private $m_szCountryNameShort;
		private $m_nISOCode;
		private $m_nListPriority;
		
		//public properties
		public function getCountryName()
		{
			return $this->m_szCountryName;
		}
		public function getCountryNameShort()
		{
			return $this->m_szCountryNameShort;
		}
		public function getISOCode()
		{
			return $this->m_nISOCode;
		}
		public function getListPriority()
		{
			return $this->m_nListPriority;
		}
		
		//constructor
		public function __construct($nISOCode, $szCountryName, $szCountryNameShort, $nListPriority)
		{
			if (!is_int($nISOCode) ||
				!is_string($szCountryName) ||
				!is_string($szCountryNameShort) ||
				!is_int($nListPriority))
			{
				throw new Exception('Invalid parameter type');
			}
				
			$this->m_nISOCode = $nISOCode;
			$this->m_szCountryName = $szCountryName;
			$this->m_szCountryNameShort = $szCountryNameShort;
			$this->m_nListPriority = $nListPriority;
		}
	}

	class ISOCountryList
	{
		private $m_licISOCountries;
		
		public function getISOCountry($nISOCode, ISOCountry & $icISOCountry)
		{
			$boFound = false;
			$nCount = 0;
			$icISOCountry2;
			
			$icISOCountry = null;
			
			if (!is_int($nISOCode))
			{
				throw new Exception('Invalid parameter type:$nISOCode');
			}
			
			while(!$boFound &&
					$nCount < count($this->m_licISOCountries))
			{
				$icISOCountry2 = ISOCountry ($this->m_licISOCountries[$nCount]);
						
				if ($nISOCode == $icISOCountry2->getISOCode())
			    {
			    	$icISOCountry = new ISOCountry($icISOCountry2->getISOCode(), $icISOCountry2->getCountryName(), $icISOCountry2->getCountryNameShort(), $icISOCountry2->getListPriority());
			    	$boFound = true;
			    }
			                
		        $nCount++;
			}
					
			return $boFound;
		}
		
		public function getCount()
		{
			return count($this->m_licISOCountries);
		}
		
		public function getAt($nIndex)
		{
			if ($nIndex < 0 ||
				$nIndex >= count($this->m_licISOCountries))
			{
				throw new Exception('Array index out of bounds');
			}
				
			return $this->m_licISOCountries[$nIndex];
		}
		
		public function add($nISOCode, $szCountryName, $szCountryNameShort, $nListPriority)
		{
			$newISOCountry = new ISOCountry($nISOCode, $szCountryName, $szCountryNameShort, $nListPriority);

			$this->m_licISOCountries[] = $newISOCountry;
		}

		//constructor
		public function __construct()
		{
	        $this->m_licISOCountries = array();
		}
	}

	class ISOCurrency
	{
		private $m_szCountryName;
	   	private $m_nExponent;
	    private $m_nISOCode;
	    private $m_szCurrency;
	    private $m_szCurrencyShort;
	    private $m_boInUse;
	    
	    //public properties
	    public function getCountryName()
	    {
	    	return $this->m_szCountryName;
	    }
	    
	    public function getExponent()
	    {
	    	return $this->m_nExponent;
	    }
	   
	    public function getCurrency()
	    {
	    	return $this->m_szCurrency;
	    }
	   
	    public function getCurrencyShort()
	    {
	    	return $this->m_szCurrencyShort;
	    }
	   
	    public function getISOCode()
	    {
	    	return $this->m_nISOCode;
	    }
	    
	    public function getInUse()
	    {
	    	return $this->m_boInUse;
	    }
	    
	    //constructor
	    public function __construct($nISOCode, $szCurrency, $szCurrencyShort, $szCountryName, $nExponent, $boInUse)
	    {
	    	$this->m_nISOCode = $nISOCode;
	    	$this->m_szCountryName = $szCountryName;
	    	$this->m_nExponent = $nExponent;
	    	$this->m_szCurrency = $szCurrency;
	    	$this->m_szCurrencyShort = $szCurrencyShort;
	    	$this->m_boInUse = $boInUse;
	    }
	}

	class ISOCurrencyList
	{
		private $m_licISOCurrencies;
		
		public function getISOCurrency($nISOCurrency, ISOCurrency &$icISOCurrency)
		{
			$boFound = false;
	        $nCount = 0;
	        $icISOCurrency2;

	       	$icISOCurrency = null;
	        
	        while (!$boFound &&
	              	$nCount < count($this->m_licISOCurrencies))
	     	{
	           	$icISOCurrency2 = $this->m_licISOCurrencies[$nCount];
	            	
	        	if ($nISOCurrency == $icISOCurrency2->getISOCode())
	            {
	            	$icISOCurrency = new ISOCurrency($icISOCurrency2->getISOCode(), $icISOCurrency2->getCurrency(),$icISOCurrency2->getCurrencyShort(), $icISOCurrency2->getCountryName(), $icISOCurrency2->getExponent(), $icISOCurrency2->getInuse()); //new ISOCurrency(((ISOCurrency) m_licISOCurrencies[nCount]).ISOCode, ((ISOCurrency) m_licISOCurrencies[nCount]).Currency, ((ISOCurrency) m_licISOCurrencies[nCount]).CurrencyShort, ((ISOCurrency) m_licISOCurrencies[nCount]).CountryName, ((ISOCurrency) m_licISOCurrencies[nCount]).Exponent, ((ISOCurrency) m_licISOCurrencies[nCount]).InUse);
	                $boFound = true;
	          	}
	                
	            $nCount++;
	        }

	      	return ($boFound);
		}
		
		public function getCount()
		{
			return count($this->m_licISOCurrencies);
		}
		
		public function getAt($nIndex)
		{
			if ($nIndex < 0 ||
	         	$nIndex >= count($this->m_licISOCurrencies))
	        {
	        	throw new Exception('Array index out of bounds');
	        }
	         	
	      	return $this->m_licISOCurrencies[$nIndex];
		}
		
		public function add($nISOCode, $szCurrency, $szCurrencyShort, $szCountryName, $nExponent, $boInUse)
		{
			$newISOCurrency = new ISOCurrency($nISOCode, $szCurrency, $szCurrencyShort, $szCountryName, $nExponent, $boInUse);

			$this->m_licISOCurrencies[] = $newISOCurrency;
		}

		//constructor
		public function __construct()
		{
	        $this->m_licISOCurrencies = array();
		}
	}

	class XMLEntity
	{
		private $m_bCharCode;
		private $m_szReplacement;
		
		public function getCharCode()
		{
			return $this->m_bCharCode;
		}
		public function getReplacement()
		{
			return $this->m_szReplacement;
		}
			
		//constructor
		public function __construct($bCharCode, $szReplacement)
		{
			$this->m_bCharCode = $bCharCode;
			$this->m_szReplacement = $szReplacement;
		}
	}

	class SharedFunctions
	{
		public static function getNamedTagInTagList($szName, $xtlTagList)
		{
			$lszHierarchicalNames = null;
	        $nCount = 0;
	        $boAbort = false;
	        $boFound = false;
	        $boLastNode = false;
	        $szString;
	        $szTagNameToFind;
	        $nCurrentIndex = 0;
	        $xtReturnTag = null;
	        $xtCurrentTag = null;
	        $nTagCount = 0;
	        $xtlCurrentTagList = null;
	        $nCount2 = 0;
	        
	        if (is_null($xtlTagList))
	        {
	        	return null;
	        }
	        
	        if (count($xtlTagList) == 0)
	        {
	        	return null;
	        }
	        
	        $lszHierarchicalNames = new StringList();
	        
	        $lszHierarchicalNames = SharedFunctions::getStringListFromCharSeparatedString($szName, '.');
	        
	        $xtlCurrentTagList = $xtlTagList;
	        
	        // loop over the hierarchical list
	        for ($nCount = 0; $nCount <$lszHierarchicalNames->getCount() && !$boAbort; $nCount++)
	        {
	        	if ($nCount == ($lszHierarchicalNames->getCount() - 1))
				{
	            	$boLastNode = true;
	           	}
	                
	          	$szString = (string)$lszHierarchicalNames[$nCount];
	          	
	          	// look to see if this tag name has the special "[]" array chars
	            $szTagNameToFind = SharedFunctions::getArrayNameAndIndex(szString, $nCurrentIndex);
	            $nCurrentIndex = $nIndex;

	           	$boFound = false;
	            $nCount2 = 0;
	            
	            for ($nTagCount = 0; $nTagCount < $xtlCurrentTagList->getCount() && !$boFound; $nTagCount++)
	            {
	            	$xtCurrentTag = $xtlCurrentTagList->getXmlTagForIndex($nTagCount);
	            	
	            	// if this is the last node then check the attributes of the tag first
	            	
	            	if ($xtCurrentTag->getName() == $szTagNameToFind)
	            	{
	            		if ($nCount2 == $nCurrentIndex)
	            		{
	            			$boFound = true;
	            		}
	            		else 
	            		{
	            			$nCount2++;
	            		}
	            	}
	            	
	            	if ($boFound)
	            	{
	            		if (!$boLastNode)
	            		{
	            			$xtlCurrentTagList = $xtCurrentTag->getChildTags();
	            		}
	            		else
	            		{
	            			// don't continue the search
	            			$xtReturnTag = $xtCurrentTag;
	            		}
	            	}
	            }
	            
	            if (!$boFound)
	            {
	            	$boAbort = true;
	            }
	        }
	        
	        return $xtReturnTag;
		}
		
		public static function getStringListFromCharSeparatedString($szString, $cDelimiter)
		{
			$nCount = 0;
	        $nLastCount = -1;
	        $szSubString;
	        $nStringLength;
	        $lszStringList;
	        
	        if ($szString == null ||
	        	$szString == "" ||
	         	(string)$cDelimiter == "")
	      	{
	        	return null;
	       	}
	            
	      	$lszStringList = new StringList();
	      	
	      	$nStringLength = strlen($szString);
	      	
	      	for ($nCount = 0; $nCount < $nStringLength; $nCount++)
	      	{
	      		if ($szString[$nCount] == $cDelimiter)
	      		{
	      			$szSubString = substr($szString, ($nLastCount + 1), ($nCount - $nLastCount - 1));
	      			$nLastCount = $nCount;
	      			$lszStringList->add($szSubString);
	      			
	      			if ($nCount == $nStringLength)
	      			{
	      				$lszStringList->add('');
	      			}
	      		}
	      		else 
	      		{
	      			if ($nCount == ($nStringLength - 1))
	      			{
	      				$szSubString = substr($szString, ($nLastCount + 1), ($nCount - $nLastCount));
	      				$lszStringList->add($szSubString);
	      			}
	      		}
	      	}
	      	
	      	return $lszStringList;
		}
		
		public static function getValue($szXMLVariable, $xtlTagList, & $szValue)
		{
			$boReturnValue = false;
	        $lszHierarchicalNames;
	        $szXMLTagName;
	        $szLastXMLTagName;
	        $nCount = 0;
	        $xtCurrentTag = null;
	        $xaXmlAttribute = null;
	        $lXmlTagAttributeList;
			
			if (xtlTagList == null)
	       	{
	        	$szValue = null;
	            return (false);
	       	}
	       	
	       	$lszHierarchicalNames = new StringList();
	        $szValue = null;
	        $lszHierarchicalNames = SharedFunctions::getStringListFromCharSeparatedString($szXMLVariable, '.');
	        
			if (count($lszHierarchicalNames) == 1)
	     	{
	       		$szXMLTagName = $lszHierarchicalNames->getAt(0);

	            $xtCurrentTag = SharedFunctions::GetNamedTagInTagList($szXMLTagName, $xtlTagList);

	            if ($xtCurrentTag != null)
	            {
	            	$lXmlTagAttributeList = $xtCurrentTag->getAttributes();
	              	$xaXmlAttribute = $lXmlTagAttributeList->getAt($szXMLTagName);

	                if ($xaXmlAttribute != null)
	                {
	                  	$szValue = $xaXmlAttribute->getValue();
	                    $boReturnValue = true;
	                }
	                else
	                {
	                    $szValue = $xtCurrentTag->getContent();
	                    $boReturnValue = true;
	                }
	            }
	    	}
	    	else 
	    	{
	    		if (count($lszHierarchicalNames) > 1)
	          	{
	            	$szXMLTagName = $lszHierarchicalNames->getAt(0);
	                $szLastXMLTagName = $lszHierarchicalNames->getAt(($lszHierarchicalNames->getCount() - 1));

	                // need to remove the last variable from the passed name
	                for ($nCount = 1; $nCount < ($lszHierarchicalNames->getCount() - 1); $nCount++)
	                {
	                	$szXMLTagName .= "." . $lszHierarchicalNames->getAt($nCount);
	               	}

	               	$xtCurrentTag = SharedFunctions::getNamedTagInTagList($szXMLTagName, $xtlTagList);

	                // first check the attributes of this tag
	                if ($xtCurrentTag != null)
	                {
	                	$lXmlTagAttributeList = $xtCurrentTag->getAttributes();
	                    $xaXmlAttribute = $lXmlTagAttributeList->getXmlAttributeForAttributeName($szLastXMLTagName);

	                    if ($xaXmlAttribute != null)
	                    {
	                    	$szValue = $xaXmlAttribute->getValue();
	                      	$boReturnValue = true;
	                  	}
	                    else
	                    {
	                    	// check to see if it's actually a tag
	                        $xtCurrentTag = SharedFunctions::getNamedTagInTagList($szLastXMLTagName, $xtCurrentTag->getChildTags());

	                        if ($xtCurrentTag != null)
	                        {
	                        	$szValue = SharedFunctions::replaceEntitiesInStringWithChars($xtCurrentTag->getContent());
	                            $boReturnValue = true;
	                      	}
	                   	}
	             	}
	           	}
	    	}
	        
			return $boReturnValue;
		}
		
		public static function getArrayNameAndIndex($szName, &$nIndex)
		{
			$szReturnString;
	        $nCount = 0;
	      	$szSubString;
	       	$boFound = false;
	        $boAbort = false;
	        $boAtLeastOneDigitFound = false;
			
	        if ($szName == '')
	      	{
	        	$nIndex = 0;
	            return $szName;
	       	}

	      	$szReturnString = $szName;
	        $nIndex = 0;
	        
	        if ($szName[(strlen($szName) - 1)] == ']')
	        {
	        	$nCount = strlen($szName) - 2;

	          	while (!$boFound &&
	                	!$boAbort &&
	                  	$nCount >= 0)
	        	{
	          		// if we've found the closing array brace
		            if ($szName[$nCount] == '[')
		            {
		            	$boFound = true;
		          	}
		            else
		            {
		            	if (!is_numeric($szName[$nCount]))
		                {
		                	$boAbort = true;
		                }
		              	else
		                {
		                	$boAtLeastOneDigitFound = true;
		                    $nCount--;
		                }
		            }
	          	}
	                  	
	        	// did we finish successfully?
	          	if ($boFound &&
	                $boAtLeastOneDigitFound)
	            {
	            	$szSubString = substr($szName, ($nCount + 1), (strlen($szName) - $nCount - 2));
	                $szReturnString = substr($szName, 0, $nCount);
	                $nIndex = (int)($szSubString);
	           	}
	        }
	        
	        return $szReturnString;
		}
		
		public static function stringToByteArray($str)
		{
			$encoded;
			
			$encoded = utf8_encode($str);
			
			return $encoded;
		}
		
		public static function byteArrayToString($aByte)
		{
			return utf8_decode($aByte);
		}
		
		public static function forwardPaddedNumberString($nNumber, $nPaddingAmount, $cPaddingChar)
		{
			$szReturnString;
	        $sbString;
	        $nCount = 0;

	        $szReturnString = (string)$nNumber;
	         
	        if (strlen($szReturnString) < $nPaddingAmount &&
	        		$nPaddingAmount > 0)
	      	{
	       		$sbString = '';

				for ($nCount = 0; $nCount < ($nPaddingAmount - strlen($szReturnString)); $nCount++)
		        {
		        	$sbString .= $cPaddingChar;   
		        }
		                
		      	$sbString .= $szReturnString;
		        $szReturnString = (string)$sbString;
	        }
	           		
	      	return $szReturnString;
		}
		
		public static function stripAllWhitespace($szString)
		{
			$sbReturnString;
	        $nCount = 0;

	        if ($szString == null)
	        {
	        	return (null);
	        }
	         
	        $sbReturnString = '';
	         
	        for ($nCount = 0; $nCount < strlen($szString); $nCount++)
	      	{
	        	if ($szString[$nCount] != ' ' &&
	            	$szString[$nCount] != '\t' &&
	                $szString[$nCount] != '\n' &&
	                $szString[$nCount] != '\r')
	          	{
	            	$sbReturnString .= $szString[$nCount];
	           	}
	       	}
	            
	      	return (string)$sbReturnString;
		}
		
		public static function getAmountCurrencyString($nAmount, $nExponent)
		{
			$szReturnString = "";
	      	$lfAmount;
	       	$nDivideAmount;

	       	$nDivideAmount = (int)(pow(10, $nExponent));
	        $lfAmount = (double)($nAmount/$nDivideAmount);
	        $szReturnString = (string)$lfAmount;

	       	return ($szReturnString);
		}
		
		public static function isStringNullOrEmpty($szString)
		{
			$boReturnValue = false;

			if ($szString == null ||
				$szString == '')
			{
				$boReturnValue = true;
			}
				
			return ($boReturnValue);
		}
		
		public static function replaceCharsInStringWithEntities($szString)
		{
			//give access to enum like associated array
			global $g_XMLEntities;
			
			$szReturnString;
	      	$nCount;
	      	$boFound;
	       	$nHTMLEntityCount;

	      	$szReturnString = null;
	      	
	      	for ($nCount = 0; $nCount < strlen($szString); $nCount++)
	      	{
	      		$boFound = false;
	           	$nHTMLEntityCount = 0;
				
	           	while (!$boFound && 
	                  	$nHTMLEntityCount < count($g_XMLEntities))
	            {
	            	//$test1 = htmlspecialchars('&');
	                  		
	                if ($g_XMLEntities[$nHTMLEntityCount]->getReplacement() == htmlspecialchars($szString[$nCount]))
	                {
	                	$boFound = true;
	                }
	                else 
	                {
	                	$nHTMLEntityCount++;
	                }
	          	}
	                  	
	        	if ($boFound)
	        	{
	        		$szReturnString .= $g_XMLEntities[$nHTMLEntityCount]->getReplacement();
	        	}
	        	else 
	        	{
	        		$szReturnString .= $szString[$nCount];
	        	}
	      	}
	        
	      	return $szReturnString;
		}
		
		public static function replaceEntitiesInStringWithChars($szString)
		{
			$szReturnString = null;
	        $nCount;
	        $boFound = false;
	        $boFoundAmpersand = false;
	        $nHTMLEntityCount;
	        $szAmpersandBuffer = "";
	        $nAmpersandBufferCount = 0;
	        
	        for ($nCount = 0; $nCount < strlen($szString); $nCount++)
	        {
	        	$boFound = false;
	            $nHTMLEntityCount = 0;

	          	if (!$boFoundAmpersand)
	           	{
	            	if ($szString[$nCount] == '&')
	                {
	                	$boFoundAmpersand = true;
	                    $szAmpersandBuffer = (string)$szString[$nCount];
	                   	$nAmpersandBufferCount = 0;
	                }
	                else
	                {
	                	$szReturnString .= $szString[$nCount];
	                }
	            }
	            else 
	            {
	            	$szAmpersandBuffer .= $szString[$nCount];

	               	if ($nAmpersandBufferCount < (10 - 2))
	                {
	                	if ($szString[$nCount] == ';')
	                    {
	                    	$boFound = true;
	                        $boFoundAmpersand = false;
	                    }
	                    else
	                    {
	                        $nAmpersandBufferCount++;
	                    }
	                }
	                else
	                {
	                    $szReturnString .= $szAmpersandBuffer;
	                    $boFoundAmpersand = false;
	                }
	            }
	            
	            if ($boFound)
	           	{
	           		// need to find the entity in the list
	            	$boFoundEntity = false;
	                $nXMLEntityCount = 0;
	                
	                while (!$boFoundEntity &&
	                      	$nXMLEntityCount < count($g_XMLEntities))
	              	{
	                	if (strtoupper($g_XMLEntities[$nXMLEntityCount]->getReplacement()) == strtoupper($szAmpersandBuffer))
	                    {
	                    	$boFoundEntity = true;
	                    }
	                    else
	                    {
	                         $nXMLEntityCount++;
	                    }
	                }
	                
	                if ($boFoundEntity)
	              	{
	                	$szReturnString .= $g_XMLEntities[$nXMLEntityCount]->getCharCode();
	                }
	                else
	                {
	                 	$szReturnString .= $szAmpersandBuffer;
	                }
	                $boFound = false;
	            }
	        }
	        
	        if ($boFoundAmpersand && !$boFound)
	       	{
	        	$szReturnString .= $szAmpersandBuffer;
	      	}

	        return $szReturnString;
		}
		
		public static function boolToString($boValue)
		{
			if ($boValue == true)
			{
				return 'true';
			}
			elseif ($boValue == false)
			{
				return 'false';
			}
		}
	}

	//SOAP

	//accessing external files
	class SOAPNamespace
	{	
		private $m_szNamespace;
	    private $m_szPrefix;
		
	    public function getNamespace()
	    {
	     	return $this->m_szNamespace;
	    }
	    public function getPrefix()
	    {
	      	return $this->m_szPrefix;
	    }
	     
	    public function __construct($szPrefix,$szNamespace)
	    {
	    	$this->m_szNamespace = $szNamespace;
	    	$this->m_szPrefix = $szPrefix;
	    }
	}

	class SOAPNamespaceList
	{
		private $m_lsnSOAPNamespaceList;
		
		function getAt($nIndex)
		{
			if ($nIndex < 0 ||
			   $nIndex >= count($this->m_lsnSOAPNamespaceList))
			{
				throw new Exception('Array index out of bounds');
			}
			
			return $this->m_lsnSOAPNamespaceList[$nIndex];
		}
		
		function getCount()
		{
			return count($this->m_lsnSOAPNamespaceList);
		}
		
		private function add1(SOAPNamespace $snSOAPNamespace)
		{
			$this->m_lsnSOAPNamespaceList[] = $snSOAPNamespace;
		}
		private function add2($szPrefix, $szSOAPNamespace)
		{
			if (!is_string($szPrefix) || !is_string($szSOAPNamespace))
			{
				throw new Exception('Invalid parameter type');
			}
			
			$this->m_lsnSOAPNamespaceList[] = new SOAPNamespace($szPrefix, $szSOAPNamespace);
		}
		
		//function overloading
		public function add()
		{
			$num_args = func_num_args();
			$args = func_get_args();
			
			switch ($num_args)
			{
				case 1:
					//$this->__call('add1', $args);
					$this->add1($args[0]);
					break;
				case 3:
					//$this->__call('add2', $args);
					$this->add2($args[0], $args[1], $args[2]);
					break;
					default:
						throw new Exception('Invalid number of parameters for fucntion Add');
			}
		}
		
		//constructor
		public function __construct()
		{
			$this->m_lsnSOAPNamespaceList = array();
		}
	}

	class SOAPParameter
	{
		private $m_szName;
	  	private $m_szValue;
	 	//private $m_lspaSOAPParamAttributeList = array();
	 	private $m_lspaSOAPParamAttributeList;
	   	private $m_lspSOAPParamList;
	   	
	   	//public property functions
	   	public function getName()
	   	{
	   		return $this->m_szName;
	   	}
	   	public function getValue()
	   	{
			return $this->m_szValue;   		
	   	}
	   	public function setValue($szValue)
	   	{
			$this->m_szValue = $szValue;	
	   	}
	   	public function getSOAPParamAttributeList()
	   	{
	   		return $this->m_lspaSOAPParamAttributeList;
	   	}
	   	public function getSOAPParamList()
	   	{
	   		return $this->m_lspSOAPParamList;
	   	}
	   	
	   	//constructor
	   	public function __construct($szName, $szValue, SOAPParamAttributeList $lspaSOAPParamAttributeList = null)
	   	{
	   		$nCount = 0;
	   		$spaSOAPParamAttribute = null;
	   		
	   		if (!is_string($szName) ||
	   			!is_string($szValue))
	   		{
	   			throw new Exception('Invalid parameter type');
	   		}
	   		
	   		$this->m_szName = $szName;
	   		//$this->m_szValue = SharedFunctions::replaceCharsInStringWithEntities($szValue);
	   		$this->setValue($szValue);
	   		
	   		$this->m_lspSOAPParamList = new SOAPParamList();
			$this->m_lspaSOAPParamAttributeList = new SOAPParamAttributeList();
	   		
	   		if ($lspaSOAPParamAttributeList != null)
	   		{
	   			for ($nCount = 0; $nCount < $lspaSOAPParamAttributeList->getCount();$nCount++)
	   			{
	   				$spaSOAPParamAttribute = new SOAPParamAttribute($lspaSOAPParamAttributeList->getAt($nCount)->getName(), $lspaSOAPParamAttributeList->getAt($nCount)->getValue());
	   				
	   				$this->m_lspaSOAPParamAttributeList->add($spaSOAPParamAttribute);
	   			}
	   		}
	   	}
	   	
	   	function toXMLString()
	   	{
	   		$sbReturnString = null;
	   		$nCount = null;
	   		$spParam = null;
	   		$spaAttribute = null;
	   		$sbString = null;
	   		
	   		$sbReturnString = '';
	   		$sbReturnString .= '<' . $this->getName();
	   		
	   		if ($this->m_lspaSOAPParamAttributeList != null)
	   		{
	   			for ($nCount = 0; $nCount < $this->m_lspaSOAPParamAttributeList->getCount(); $nCount++)
	   			{
	   				$spaAttribute = $this->m_lspaSOAPParamAttributeList->getAt($nCount);
	   				
	   				if ($spaAttribute != null)
		   			{
		   				$sbString = '';
		   				$sbString .= ' ' .$spaAttribute->getName(). '="' .SharedFunctions::replaceCharsInStringWithEntities($spaAttribute->getValue()). '"';
		   				$sbReturnString .= (string)$sbString;
		   			}
	   			}
	   		}
	   		
	   		if ($this->m_lspSOAPParamList->getCount() == 0 &&
	   		    $this->getValue() == '')
	   		{
	   			$sbReturnString .= ' />';
	   		}
	   		else
	   		{
	   			$sbReturnString .= '>';
	   			
	   			if ($this->getValue() != '')
	   			{
	   				$sbReturnString .= SharedFunctions::replaceCharsInStringWithEntities($this->getValue());
	   			}
	   			
	   			for ($nCount = 0; $nCount < $this->m_lspSOAPParamList->getCount(); $nCount++)
	   			{
	   				$spParam = $this->m_lspSOAPParamList->getAt($nCount);
	   				
	   				if ($spParam != null)
	   				{
	   					$sbReturnString .= $spParam->toXMLString();
	   				}
	   			}
	   			
	   			$sbReturnString .= '</' . $this->getName() . '>';
	   		}
	   		
	   		return (string)$sbReturnString;
	   	}
	}

	class SOAPParamList
	{
		private $m_lspSOAPParamList;
		
		public function getAt($nIndex)
		{
			if ($nIndex < 0 ||
				$nIndex > count($this->m_lspSOAPParamList))
			{
				throw new Exception('Array index out of bounds');
			}
			
			return $this->m_lspSOAPParamList[$nIndex];
		}
		
		function getCount()
		{
			return count($this->m_lspSOAPParamList);
		}
		
		protected function add1(SOAPParameter $spSOAPParam)
		{
			$this->m_lspSOAPParamList[] = $spSOAPParam;
		}
		protected function add2($szName, $szValue)
		{
			$nReturnValue = -1;
			
			if (!is_string($szName) ||
				!is_string($szValue))
			{
				throw new Exception('Invalid parameter type: '. $szName .', '. $szValue);
			}
				
			if ($szName != '' &&
				$szName != null)
			{
				$this->m_lspSOAPParamList[] = new SOAPParameter($szName, $szValue);
			}
			
			return $nReturnValue;
		}
		
		//overloading
		public function add()
		{
			$num_args = func_num_args();
			$args = func_get_args();
			
			switch ($num_args)
			{
				case 1:
					//$this->__call('add1', $args);
					$this->add1($args[0]);
					break;
				case 2:
					//$this->__call('add2', $args);
					$this->add2($args[0], $args[1]);
					break;
					default:
						throw new Exception('Invalid number of parameters');
			}
		}
		
		//constructor
		public function __construct()
		{
			$this->m_lspSOAPParamList = array();
		}
	}

	class SOAPParamAttribute
	{
		private $m_szName;
	   	private $m_szValue;
	   	
	   	public function getName()
	   	{
	   		return $this->m_szName;
	   	}
	   	public function getValue()
	   	{
	   		return $this->m_szValue;
	   	}
	   	
	   	//constructor
	   	public function __construct($szName, $szValue)
	   	{
	   		if (!is_string($szName) ||
	   			!is_string($szValue))
	   		{
	   			throw new Exception('Invalid parameter type');
	   		}
	   		
	   		$this->m_szName = $szName;
	   		$this->m_szValue = $szValue;
	   	}
	}

	class SOAPParamAttributeList
	{
		private $m_lspaSOAPParamAttributeAttributeList;
		
		public function getAt($nIndex)
		{
			if ($nIndex < 0 ||
				$nIndex >= count($this->m_lspaSOAPParamAttributeAttributeList))
			{
				throw new Exception('Array index out of bounds');
			}
			
			return $this->m_lspaSOAPParamAttributeAttributeList[$nIndex];
		}
		public function getCount()
		{
			return count($this->m_lspaSOAPParamAttributeAttributeList);
		}
		
		private function add1(SOAPParamAttribute $spaSOAPParamAttributeAttribute)
		{
			$result = array_push($this->m_lspaSOAPParamAttributeAttributeList, $spaSOAPParamAttributeAttribute);
			return $result;
		}
		private function add2($szName, $szValue)
		{
			$nReturnValue = -1;
			
			if (!is_string($szName) ||
				!is_string($szValue))
			{
				throw new Exception('Invalid parameter type');
			}
			
			if ($szName != '' &&
				$szName != null)
			{
				$nReturnValue = array_push($this->m_lspaSOAPParamAttributeAttributeList, new SOAPParamAttribute($szName, $szValue));
			}
			
			return $nReturnValue;
		}
		
		
		public function add()
		{
			$num_args = func_num_args();
			$args = func_get_args();
			
			switch ($num_args)
			{
				case 1:
					//$this->__call('add1', $args);
					$this->add1($args[0]);
					break;
				case 2:
					//$this->__call('add2', $args);
					$this->add2($args[0], $args[1]);
					break;
					default:
						throw new Exception('Invalid number of parameters for fucntion Add');
			}
		}
		
		//constructor
		public function __construct()
		{
			$this->m_lspaSOAPParamAttributeAttributeList = array();
		}
	}

	class SOAP
	{
		private $m_szMethod;
	    private $m_szMethodURI;
	    private $m_szURL;
	    private $m_szActionURI;
	    private $m_szSOAPEncoding;
	    private $m_boPacketBuilt;
	    private $m_szLastResponse;
	    private $m_szSOAPPacket;
	    private $m_xmlParser;
	    private $m_xmlTag;
	    private $m_nTimeout;
	    private $m_eLastException;
	    
	    private $m_lsnSOAPNamespaceList;
	    private $m_lspSOAPParamList;
	    
	    //public property like functions
	    public function getMethod()
	    {
	    	return $this->m_szMethod;
	    }
	    public function getMethodURI()
	    {
	    	return $this->m_szMethodURI;
	    }
	    public function getURL()
	    {
	    	return $this->m_szURL;
	    }
	    public function setURL($value)
	    {
			$this->m_szURL = $value;
	    }
	    public function getActionURI()
	    {
	    	return $this->m_szActionURI;
	    }
	    public function getSOAPEncoding()
	    {
	    	return $this->m_szSOAPEncoding;
	    }
	    public function getPacketBuilt()
	    {
	    	return $this->m_boPacketBuilt;
	    }
	    public function getLastResponse()
	    {
	    	return $this->m_szLastResponse;
	    }
	    public function getSOAPPacket()
	    {
	    	return $this->m_szSOAPPacket;
	    }
	    public function getXmlTag()
	    {
	    	return $this->m_xmlTag;
	    }
	    public function getTimeout()
	    {
	    	return $this->m_nTimeout;
	    }
	    public function setTimeout($value)
	    {
	    	$this->m_nTimeout = $value;
	    }
	    public function getLastException()
	    {
	    	$this->m_eLastException;
	    }
	    
	    public function buildPacket()
	    {
	    	$sbString = null;
	    	$sbString2 = null;
	    	$snNamespace = null;
	    	$szFirstNamespace = null;
	    	$szFirstPrefix = null;
	    	$nCount = 0;
	    	$spSOAPParam = null;
	    	
	    	// build the xml SOAP request
	        // start with the XML version
	    	$sbString = '';
	    	$sbString .= '<?xml version="1.0" encoding="utf-8" ?>';
	    	
	    	if ($this->m_lsnSOAPNamespaceList->getCount() == 0)
	    	{
	    		$szFirstNamespace = 'http://schemas.xmlsoap.org/soap/envelope/';
	    		$szFirstPrefix = 'soap';
	    	}
			else
			{
				$snNamespace = $this->m_lsnSOAPNamespaceList->getAt(0);
				
				if ($snNamespace == null)
				{
					$szFirstNamespace = 'http://schemas.xmlsoap.org/soap/envelope/';
					$szFirstPrefix = 'soap';
				}
				else 
				{
					if ($snNamespace->getNamespace() == null ||
						$snNamespace->getNamespace() == '')
					{
						$szFirstNamespace = 'http://schemas.xmlsoap.org/soap/envelope/';
					}
					else 
					{
						$szFirstNamespace = $snNamespace->getNamespace();
					}
					
					if ($snNamespace->getPrefix() == null ||
						$snNamespace->getPrefix() == '')
					{
						$szFirstPrefix = 'soap';
					}
					else 
					{
						$szFirstPrefix = $snNamespace->getPrefix();
					}
				}
			}
			
			$sbString2 = '';
			$sbString2 .= '<' .$szFirstPrefix. ':Envelope xmlns:' .$szFirstPrefix. '="' .$szFirstNamespace. '"';
			
			for ($nCount = 1; $nCount <$this->m_lsnSOAPNamespaceList->getCount(); $nCount++)
			{
				$snNamespace = $this->m_lsnSOAPNamespaceList->getAt($nCount);
				
				if ($snNamespace != null)
				{
					if ($snNamespace->getNamespace() != '' &&
						$snNamespace->getPrefix() != '')
					{
						$sbString2 .= ' xmlns:' .$snNamespace->getPrefix(). '="' .$snNamespace->getNamespace(). '"';
					}
				}
			}
			
			$sbString2 .= '>';
			
			$sbString .= (string)$sbString2;
			$sbString2 = '';
			$sbString2 .= '<' .$szFirstPrefix. ':Body>';
			$sbString .= (string)$sbString2;
			$sbString2 = '';
			$sbString2 .= '<' .$this->getMethod(). ' xmlns="' .$this->getMethodURI(). '">';
			$sbString .= (string)$sbString2;
			
			for ($nCount = 0;$nCount < $this->m_lspSOAPParamList->getCount(); $nCount++)
			{
				$spSOAPParam = $this->m_lspSOAPParamList->getAt($nCount);
				
				if ($spSOAPParam != null)
				{
					$sbString .= $spSOAPParam->toXMLString();	
				}
			}
			
			$sbString2 = '';
			$sbString2 .= '</' .$this->getMethod(). '>';
			$sbString .= (string)$sbString2;
			$sbString2 = '';
			$sbString2 .= '</' .$szFirstPrefix. ':Body></' .$szFirstPrefix. ':Envelope>';
			$sbString .= (string)$sbString2;
			
			$this->m_szSOAPPacket = (string)$sbString;
			$this->m_boPacketBuilt = true;
	    }
	    
	    public function sendRequest(&$ResponseDocument, &$ResponseMethod)
	    {
	    	$szString = '';	//response string
	    	$sbString;
	    	$XmlDoc;		//response in parsed array format
	    	$boReturnValue = false;
	    	$szUserAgent = 'ThePaymentGateway SOAP Library PHP';
	    	
	    	
	    	if (!$this->m_boPacketBuilt)
	    	{
	    		$this->buildPacket();
	    	}
	    	
	    	$this->m_xmlParser = null;
	    	$this->m_xmlTag = null;
	    	
	    	try
	    	{
		    	//intialising the curl for XML parsing
		    	$cURL = curl_init();
		    	
		    	//http settings
		    	$HttpHeader[] = 'SOAPAction:'. $this->getActionURI();
		    	$HttpHeader[] = 'Content-Type: text/xml; charset = utf-8';
		    	$HttpHeader[] = 'Connection: close';
		    	
		    	/*$http_options = array(	CURLOPT_HEADER			=> false,
	        							CURLOPT_HTTPHEADER		=> $HttpHeader,
	        							CURLOPT_POST			=> true,
	        							CURLOPT_URL				=> $this->getURL(),
	        							CURLOPT_USERAGENT      	=> $szUserAgent,
	        							CURLOPT_POSTFIELDS		=> $this->getSOAPPacket(),
	        							CURLOPT_RETURNTRANSFER	=> true,
	        							CURLOPT_ENCODING		=> "UTF-8",
	        							CURLOPT_SSL_VERIFYPEER	=> false,	//disabling default peer SSL certificate verification
	        							);
	        							
	        	curl_setopt_array($cURL, $http_options);*/
	        							
	        	curl_setopt($cURL, CURLOPT_HEADER, false);
	        	curl_setopt($cURL, CURLOPT_HTTPHEADER, $HttpHeader);
	        	curl_setopt($cURL, CURLOPT_POST, true);
	        	curl_setopt($cURL, CURLOPT_URL, $this->getURL());
	        	curl_setopt($cURL, CURLOPT_USERAGENT, $szUserAgent);
	        	curl_setopt($cURL, CURLOPT_POSTFIELDS, $this->getSOAPPacket());
	        	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	        	curl_setopt($cURL, CURLOPT_ENCODING, "UTF-8");
	        	curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
	        	
	        	if ($this->getTimeout() != null)
				{
					curl_setopt($cURL, CURLOPT_TIMEOUT, $this->getTimeout());
				}
				
				//$this->m_szLastResponse = curl_exec($cURL);
				$szString = curl_exec($cURL);
				$errorNo = curl_errno($cURL);//test
				$errorMsg = curl_error($cURL);//test
				$header = curl_getinfo($cURL);//test
				curl_close($cURL);
				
				$this->m_szLastResponse = $szString;
				
				$szString = str_replace("<soap:Body>", '" "', $szString);
				$szString = str_replace("</soap:Body>", '" "', $szString);
				
				
				$XmlDoc = new SimpleXMLElement($szString);
				$ResponseDocument = $XmlDoc;
				$ResponseMethod = $this->getMethod(). 'Response';
				
				$boReturnValue = true;
	    	}
	    	catch (Exception $exc)
	    	{
	    		$boReturnValue = false;
	    		$m_eLastException = $exc;
	    	}
			
			return $boReturnValue;
	    }
	    
	    public function addParam1($szName, $szValue, SOAPParamAttributeList $lspaSOAPParamAttributeList = null)
	    {
	    	$spSOAPParam;
	    	
	    	$spSOAPParam = new SOAPParameter($szName, $szValue, $lspaSOAPParamAttributeList);
	    	
	    	$this->addParam2($spSOAPParam, true);
	    }
	    public function addParam2(SOAPParameter $spSOAPParam, $boOverWriteValue)
	    {
			$lszHierarchicalNames;
			$nCurrentIndex = 0;
			$szTagNameToFind;
			$szString;
			$nCount = 0;
			$nCount2 = 0;
			$lspParamList;
			$spWorkingSOAPParam;
			$spNewSOAPParam;
			$boFound = false;
			$lspaAttributeList;
			$spaAttribute;
			$spaNewAttribute;
			$spaSOAPParamAttributeList;

			// need to check the name of the incoming item to see if it is a
	       	// complex soap parameter
	        $lszHierarchicalNames = new StringList();
	        
	        $lszHierarchicalNames = SharedFunctions::getStringListFromCharSeparatedString($spSOAPParam->getName(), '.');
	        
	        if ($lszHierarchicalNames->getCount() == 1)
	        {
	        	$this->m_lspSOAPParamList->add($spSOAPParam);
	        }
	        else 
	        {
	        	$lspParamList = $this->m_lspSOAPParamList;
	        	
	        	//complex
	        	for ($nCount = 0; $nCount < $lszHierarchicalNames->getCount(); $nCount++)
	        	{
	        		// get the current tag name
	               	$szString = (string)$lszHierarchicalNames->getAt($nCount);
	              	//continuework
	               	$szTagNameToFind = SharedFunctions::getArrayNameAndIndex($szString, $nCurrentIndex);

	             	// first thing is to try to find the tag in the list
	             	if ($boFound ||
	             		$nCount == 0)
	             	{
	             		// try to find this tag name in the list
	                    $spWorkingSOAPParam = Functions::isSOAPParamInParamList($lspParamList, $szTagNameToFind, $nCurrentIndex);

	                    if ($spWorkingSOAPParam == null)
	                    {
	                    	$boFound = false;
	                    }
	                    else 
	                	{
	                    	$boFound = true;

	                        // is this the last item in the hierarchy?
	                        if ($nCount == ($lszHierarchicalNames->getCount() - 1))
	                        {
	                        	if ($boOverWriteValue)
	                            {
	                            	// change the value
	                                $spWorkingSOAPParam->setValue($spSOAPParam->getValue());
	                           	}

	                            // add the attributes to the list
	                            for ($nCount2 = 0; $nCount2 < $spSOAPParam->getSOAPParamAttributeList()->getCount(); $nCount2++)
	                            {
	                            	//$spaAttribute = $spaSOAPParamAttributeList[$nCount2];
	                                $spaAttribute = $spSOAPParam->getSOAPParamAttributeList()->getAt($nCount2);

	                              	if ($spaAttribute != null)
	                                {
	                                	$spaNewAttribute = new SOAPParamAttribute($spaAttribute->getName(), $spaAttribute->getValue());

										$spWorkingSOAPParam->getSOAPParamAttributeList()->add($spaNewAttribute);
	                              	}
	                           	}
	                      	}
	                        $lspParamList = $spWorkingSOAPParam->getSOAPParamList();
	                  	}
	             	}
	             		
	             	if (!$boFound)
	                {
	                	// is this the last tag?
	                    if ($nCount == ($lszHierarchicalNames->getCount() - 1))
	                    {
	                    	$lspaAttributeList = new SOAPParamAttributeList();
	                            
	                        for ($nCount2 = 0; $nCount2 < $spSOAPParam->getSOAPParamAttributeList()->getCount(); $nCount2++)
	                        {
	                        	$spaSOAPParamAttributeList = $spSOAPParam->getSOAPParamAttributeList();
	                               	
	                            $spaAttribute = $spaSOAPParamAttributeList->getAt( $nCount2);

	                            if ($spaAttribute != null)
	                            {
	                            	$spaNewAttribute = new SOAPParamAttribute($spaAttribute->getName(), $spaAttribute->getValue());
	                                $lspaAttributeList->add($spaNewAttribute);
	                            }
	                      	}

	                        $spNewSOAPParam = new SOAPParameter($szTagNameToFind, $spSOAPParam->getValue(), $lspaAttributeList);

	                        $lspParamList->add($spNewSOAPParam);
	                 	}
	                    else
	                    {
	                    	$spNewSOAPParam = new SOAPParameter($szTagNameToFind, '', null);
	                        $lspParamList->add($spNewSOAPParam);
	                        $lspParamList = $spNewSOAPParam->getSOAPParamList();
	                    }
	              	}
	        	}
	        }
	        
	        $this->m_boPacketBuilt = false;
	    }
	    
	   	//overloading for addParam
	    public function addParam()
	    {
	    	//number of parameters passed into addParam()
	    	$num_args = func_num_args();
	    	//array of parameters passed into addParam()
			$args = func_get_args();
			
			switch ($num_args)
			{
				case 2:
					if (is_string($args[0]) &&
						is_string($args[1]))
					{
						//$this->__call('addParam1',$args);
						//$this->addParam1($args[0], $args[1], $args[3]);
						$this->addParam1($args[0], $args[1], null);
					}
					elseif ($args[0] instanceof SOAPParameter &&
							is_bool($args[1]))
					{
						//$this->__call('addParam2', $args);
						$this->addParam2($args[0], $args[1]);
					}
					else 
					{
						throw new Exception('Invalid parameter list for function: addParam');
					}
					break;
				case 3:
					//$this->__call('addParam1', $args);
					$this->addParam1($args[0], $args[1], $args[2]);
					break;
					default:
						throw new Exception('Invalid number of parameters for function Add');
			}
	    }
	    
	    private function addParamAttribute1($szName, $szParamAttributeName, $szParamAttributeValue)
	    {
	    	$spSOAPParam;
	    	$lspaSOAPParamAttributeList;
	    	$spaSOAPParamAttribute;
	    	
	    	if (!is_string($szName) ||
	    		!is_string($szParamAttributeName) ||
	    		!is_string($szParamAttributeValue))
	    	{
	    		throw new Exception('Invalid parameter type');
	    	}
	    	
	    	$lspaSOAPParamAttributeList = new SOAPParamAttributeList();
	    	$spaSOAPParamAttribute = new SOAPParamAttribute($szParamAttributeName, $szParamAttributeValue);
	    	$lspaSOAPParamAttributeList->add($spaSOAPParamAttribute);
	    	
	    	$spSOAPParam = new SOAPParameter($szName, '', $lspaSOAPParamAttributeList);
	    	
	    	$this->addParam2($spSOAPParam, false);
	    }
	    private function addParamAttribute2($szName, SOAPParamAttribute $spaSOAPParamAttribute)
	    {
	    	$spSOAPParam;
	    	$lspaSOAPParamAttributeList;
	    	
	    	$lspaSOAPParamAttributeList = new SOAPParamAttributeList();
	    	$lspaSOAPParamAttributeList->add($spaSOAPParamAttribute);
	    	
	    	$spSOAPParam = new SOAPParameter($szName, '', $lspaSOAPParamAttributeList);
	    	
	    	$this->addParam2($spSOAPParam, false);
	    }
	    
	    //overloading for addParamAttribute
	    public function addParamAttribute()
	    {
	    	$num_args = func_num_args();
			$args = func_get_args();
			
			switch ($num_args)
			{
				case 2:
					//$this->__call('addParamAttribute2', $args);
					$this->addParamAttribute2($args[0], $args[1]);
					break;
				case 3:
					//$this->__call('addParamAttribute1', $args);
					$this->addParamAttribute1($args[0], $args[1], $args[2]);
					break;
					default:
						throw new Exception('Invalid number of parameters for fucntion Add');
			}
	    }
	    
	    //overloading constructor
	    private function SOAP1($szMethod, $szMethodURI)
	    {
	    	$this->SOAP3($szMethod, $szMethodURI, null, 'http://schemas.xmlsoap.org/soap/encoding/', true, null);
	    }
	    private function SOAP2($szMethod, $szMethodURI, $szURL)
	    {
	    	$this->SOAP3($szMethod, $szMethodURI, $szURL, 'http://schemas.xmlsoap.org/soap/encoding/', true, null);
	    }
	    private function SOAP3($szMethod, $szMethodURI, $szURL, $szSOAPEncoding, $boAddDefaultNamespaces, SOAPNamespaceList $lsnSOAPNamespaceList = null)
	    {
	    	$snSOAPNamespace;
	       	$nCount = 0;

	      	$this->m_szMethod = $szMethod;
	      	$this->m_szMethodURI = $szMethodURI;
	       	$this->m_szURL = $szURL;
	      	$this->m_szSOAPEncoding = $szSOAPEncoding;
	      	
	      	if ($this->m_szMethodURI != "" &&
	          	$this->m_szMethod != "")
	      	{
	       		if ($this->m_szMethodURI[(strlen($this->m_szMethodURI) - 1)] == '/')
	          	{
	              	$this->m_szActionURI = $this->m_szMethodURI . $this->m_szMethod;
	            }
	            else
	            {
	              	$this->m_szActionURI = $this->m_szMethodURI . '/' . $this->m_szMethod;
	            }
	        }
	        
	        $this->m_lsnSOAPNamespaceList = new SOAPNamespaceList();

	      	if ($boAddDefaultNamespaces)
	        {
	        	$snSOAPNamespace = new SOAPNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
	          	$this->m_lsnSOAPNamespaceList->add($snSOAPNamespace);
	            $snSOAPNamespace = new SOAPNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	            $this->m_lsnSOAPNamespaceList->add($snSOAPNamespace);
	           	$snSOAPNamespace = new SOAPNamespace('xsd', 'http://www.w3.org/2001/XMLSchema');
	            $this->m_lsnSOAPNamespaceList->add($snSOAPNamespace);
	        }
	        if ($lsnSOAPNamespaceList != null)
	      	{
	         	for ($nCount = 0; $nCount < count($lsnSOAPNamespaceList); $nCount++)
	            {
	             	$snSOAPNamespace = new SOAPNamespace($lsnSOAPNamespaceList->getAt($nCount)->getPrefix(), $lsnSOAPNamespaceList->getAt($nCount)->getNamespace());
	              	$this->m_lsnSOAPNamespaceList->add($snSOAPNamespace);
	            }
	        }
	        $this->m_lspSOAPParamList = new SOAPParamList();

	        $this->m_boPacketBuilt = false;
	    }
	    
	    //constructor
	    public function __construct()
	    {
	    	$num_args = func_num_args();
			$args = func_get_args();
			
			switch ($num_args)
			{
				case 2:
					//$this->__call('SOAP1', $args);
					$this->SOAP1($args[0], $args[1]);
					break;
				case 3:
					//$this->__call('SOAP2', $args);
					$this->SOAP2($args[0], $args[1], $args[2]);
					break;
				case 6:
					//$this->__call('SOAP3', $args);
					$this->SOAP3($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
					default:
						throw new Exception('Invalid number of parameters for constructor SOAP');
			}
	    }
	}

	class Functions
	{
		public static function isSOAPParamInParamList(SOAPParamList $lspParamList, $szTagNameToFind, $nIndex)
		{
			$spReturnParam = null;
			$boFound = false;
			$nFound = 0;
			$nCount = 0;
			$spCurrentParam = null;
			
			if ($lspParamList == null)
			{
				return (null);
			}
			
			while(!$boFound &&
					$nCount < $lspParamList->getCount())
			{
				$spCurrentParam = $lspParamList->getAt($nCount);
				
				if ($spCurrentParam->getName() == $szTagNameToFind)
				{
					if ($nFound == $nIndex)
					{
						$boFound = true;
						$spReturnParam = $spCurrentParam;
					}
					else 
					{
						$nFound++;
					}
				}
				
				$nCount++;
			}
			
			return $spReturnParam;
		}
	}



	//ThePaymentSystem


	class NullableTRANSACTION_TYPE extends Nullable 
	{
		private $m_ttValue;
	 	
	 	public function getValue()
	 	{
			if ($this->m_boHasValue == false)
			{
				throw new Exception("Object has no value");
			}
			
			return ($this->m_ttValue);
	 	}
	 	public function setValue($value)
	 	{
	 		$this->m_boHasValue = true;
	 		$this->m_ttValue = $value;
	 	}
	 	
	 	//constructor
	 	public function __construct($ttValue)
	 	{
	 		Nullable::__construct();
	 		
	 		if ($ttValue != null)
	 		{
	 			//$this->setValue($ttValue);
	 			$this->setValue($ttValue);
	 		}
	 	}
	}
	 
	class NullableCHECK_RESULT extends Nullable 
	{
	 	private $m_crValue;
	 	
	 	public function getValue()
	 	{
	 		if ($this->m_boHasValue == false)
			{
				throw new Exception("Object has no value");
			}
			return ($this->m_crValue);
	 	}
	 	public function setValue($value)
	 	{
	 		$this->m_boHasValue = true;
	 		$this->m_crValue = $value;	
	 	}
	 	
	 	//constructor
	 	public function __construct($crValue)
	 	{
	 		Nullable::__construct();
	 		
	 		if ($crValue != null)
	 		{
	 			//$this->m_crValue = $crValue;
	 			$this->setValue($crValue);
	 		}
	 	}
	}

	class NullableCARD_DATA_STATUS extends Nullable
	{
		private $m_cdsValue;

	    function getValue()
	    {
	   		if (m_boHasValue == false)
	        {
	         	throw new Exception("Object has no value");
	       	}
	         	return ($this->m_cdsValue);
	    }
	        
	    function setValue($value)
	    {
	        $this->m_boHasValue = true;
	        $this->m_cdsValue = $value;
	    }

	    function __construct($cdsValue)
	    {
	        parent::__construct();
	        	
	        if ($cdsValue != null)
	        {
	        	$this->setValue($cdsValue);
	        }
	    }
	}
	    
	/*****************/
	/* Gateway Enums */
	/*****************/
	final class CARD_TYPE
	{
		const UNKNOWN = 'UNKNOWN';
	    const AMERICAN_EXPRESS = 'AMERICAN_EXPRESS';
	    const ATM = 'ATM';
	    const JCB = 'JCB';
	    const MASTERCARD = 'MASTERCARD';
	    const PLATIMA = 'PLATIMA';
	    const DINERS_CLUB = 'DINERS_CLUB';
	    const VISA_DEBIT = 'VISA_DEBIT';
	    const SOLO = 'SOLO';
	    const VISA_ELECTRON = 'VISA_ELECTRON';
	    const VISA = 'VISA';
	    const VISA_PURCHASING = 'VISA_PURCHASING';
	    const MAESTRO = 'MAESTRO';
	    const GE_CAPITAL = 'GE_CAPITAL';
	    const LASER = 'LASER';

	    //make sure the class cannot be instantiated	
	    private function __construct()
	    {
	    }
	}
	final class TRANSACTION_TYPE
	{
		const UNKNOWN = 'UNKNOWN';
	    const SALE = 'SALE';
	    const REFUND = 'REFUND';
	    const PREAUTH = 'PREAUTH';
	    const VOID = 'VOID';
	    const COLLECTION = 'COLLECTION';
	    const RETRY = 'RETRY';
	    const STORE = 'STORE';
	    const KEEP_ALIVE = 'KEEP_ALIVE';

	    //make sure the class cannot be instantiated
	    private function __construct()
	    {
	    }
	}
	final class CHECK_RESULT
	{
		const UNKNOWN = 'UNKNOWN';
	    const PASSED = 'PASSED';
	    const FAILED = 'FAILED';
	    const PARTIAL = 'PARTIAL';
	    const ERROR = 'ERROR';
	    const NOT_SUBMITTED = 'NOT_SUBMITTED';
	    const NOT_CHECKED = 'NOT_CHECKED';
	    const NOT_ENROLLED = 'NOT_ENROLLED';

	   	//make sure the class cannot be instantiated	
	   	private function __construct()
	   	{
	   	}
	}
	final class CARD_DATA_STATUS
	{
		const UNKNOWN = 'UNKNOWN';
		const MUST_BE_SUBMITTED = 'MUST_BE_SUBMITTED';
		const DO_NOT_SUBMIT = 'DO_NOT_SUBMIT';
		const SUBMIT_ONLY_IF_ON_CARD = 'SUBMIT_ONLY_IF_ON_CARD';
		const IGNORED_IF_SUBMITTED = 'IGNORED_IF_SUBMITTED';
	}

		
	/*****************/
	/* Input classes */
	/*****************/
	class RequestGatewayEntryPoint extends GatewayEntryPoint 
	{
		private $m_nRetryAttempts;

	  	public function getRetryAttempts()
	  	{
	  		return $this->m_nRetryAttempts;
	  	}
		
		//constructor
	  	public function __construct($szEntryPointURL, $nMetric, $nRetryAttempts)
	   	{
	   		//do NOT forget to call the parent constructor too
	   		//parent::GatewayEntryPoint($szEntryPointURL, $nMetric);
	   		GatewayEntryPoint::__construct($szEntryPointURL, $nMetric);
	   		
	     	$this->m_nRetryAttempts = $nRetryAttempts;
	   	}
	}

	class RequestGatewayEntryPointList
	{
		private $m_lrgepRequestGatewayEntryPoint;
		
		public function getAt($nIndex)
		{
			if ($nIndex < 0 ||
				$nIndex >= count($this->m_lrgepRequestGatewayEntryPoint))
			{
				throw new Exception("Array index out of bounds");
			}
				
			return $this->m_lrgepRequestGatewayEntryPoint[$nIndex];
		}
		
		public function getCount()
		{
			return count($this->m_lrgepRequestGatewayEntryPoint);
		}
		
		public function sort($ComparerClassName, $ComparerMethodName)
		{
			usort($this->m_lrgepRequestGatewayEntryPoint, array("$ComparerClassName","$ComparerMethodName"));		
		}
		
		public function add($EntryPointURL, $nMetric, $nRetryAttempts)
		{
			return array_push($this->m_lrgepRequestGatewayEntryPoint, new RequestGatewayEntryPoint($EntryPointURL, $nMetric, $nRetryAttempts));
		}
		
		//constructor
		public function __construct()
		{
			$this->m_lrgepRequestGatewayEntryPoint = array();
		}
	}

	class GenericVariable
	{
		private $m_szName;
	   	private $m_szValue;

	   	public function getName()
	   	{
	   		return $this->m_szName;
	   	}
	   	public function getValue()
	   	{
	   		return $this->m_szValue;
	   	}

	   	//constructor
	   	public function __construct($szName, $szValue)
	    {
	    	$this->m_szName = $szName;
	    	$this->m_szValue = $szValue;
	    }
	}

	class GenericVariableList
	{
		private $m_lgvGenericVariableList;
		
		public function getAt($intOrStringValue)
		{
			$nCount = 0;
			$boFound = false;
			$gvGenericVariable = null;
			//$gvGenericVariable2;
			
			if (is_int($intOrStringValue))
			{
				if ($intOrStringValue < 0 ||
					$intOrStringValue >= count($this->m_lgvGenericVariableList))
				{
					throw new Exception("Array index out of bounds");
				}
				
				return $this->m_lgvGenericVariableList[$intOrStringValue];
			}
			elseif (is_string($intOrStringValue))
			{
				if ($intOrStringValue == null ||
					$intOrStringValue == '')
				{
					return (null);
				}

				while (!$boFound &&
						$nCount < count($this->m_lgvGenericVariableList))
				{
					if (strtoupper($this->m_lgvGenericVariableList[$nCount]->getName()) ==
						strtoupper($intOrStringValue))
					{
						$gvGenericVariable = $this->m_lgvGenericVariableList[$nCount];
						$boFound = true;
					}
					$nCount++;
				}

				return $gvGenericVariable;
			}
			else 
			{
				throw new Exception('Invalid parameter type:$intOrStringValue');
			}
		}
		
		public function getCount()
		{
			return count($this->m_lgvGenericVariableList);
		}
		
		public function add($Name, $szValue)
		{
			$nReturnValue = -1;
			
			if ($Name != null &&
				$Name != "")
			{
	        	$nReturnValue = array_push($this->m_lgvGenericVariableList, new GenericVariable($Name, $szValue));
			}

	        return ($nReturnValue);
		}
		
		//constructor
		public function __construct()
		{
			$this->m_lgvGenericVariableList = array();
		}
	}

	class CustomerDetails
	{
		private $m_adBillingAddress;
	    private $m_szEmailAddress;
	    private $m_szPhoneNumber;
	    private $m_szCustomerIPAddress;
	    
	    public function getBillingAddress()
	    {
	    	return $this->m_adBillingAddress;
	    }
	    public function getEmailAddress()
	    {
	    	return $this->m_szEmailAddress;
	    }
	    public function getPhoneNumber()
	    {
	    	return $this->m_szPhoneNumber;
	    }
	    public function getCustomerIPAddress()
	    {
	    	return $this->m_szCustomerIPAddress;
	    }
	    
	    //constructor
	    public function __construct($adBillingAddress = null, $szEmailAddress, $szPhoneNumber, $szCustomerIPAddress)
	    {
	    	$this->m_adBillingAddress = $adBillingAddress;
	    	$this->m_szEmailAddress = $szEmailAddress;
	    	$this->m_szPhoneNumber = $szPhoneNumber;
	    	$this->m_szCustomerIPAddress = $szCustomerIPAddress;
	    }
	}

	class AddressDetails
	{
		private $m_szAddress1;
	    private $m_szAddress2;
	    private $m_szAddress3;
	    private $m_szAddress4;
	    private $m_szCity;
	    private $m_szState;
	    private $m_szPostCode;
	    private $m_nCountryCode;
	    
	    public function getAddress1()
	    {
	    	return $this->m_szAddress1;
	    }
	    public function getAddress2()
	    {
	    	return $this->m_szAddress2;
	    }
	    public function getAddress3()
	    {
	    	return $this->m_szAddress3;
	    }
	    public function getAddress4()
	    {
	    	return $this->m_szAddress4;
	    }
	    public function getCity()
	    {
	    	return $this->m_szCity;
	    }
	    public function getState()
	    {
	    	return $this->m_szState;
	    }
	    public function getPostCode()
	    {
	    	return $this->m_szPostCode;
	    }
	    public function getCountryCode()
	    {
	  		return $this->m_nCountryCode;
	    }
	        
	    //constructor
	    public function __construct($szAddress1, $szAddress2, $szAddress3, $szAddress4, $szCity, $szState, $szPostCode, NullableInt $nCountryCode = null)
	    {
	    	$this->m_szAddress1 = $szAddress1;
	    	$this->m_szAddress2 = $szAddress2;
	    	$this->m_szAddress3 = $szAddress3;
	    	$this->m_szAddress4 = $szAddress4;
	    	$this->m_szCity = $szCity;
	    	$this->m_szState = $szState;
	    	$this->m_szPostCode = $szPostCode;
	    	$this->m_nCountryCode = $nCountryCode;
	    }
	}

	class CreditCardDate
	{
		private  $m_nMonth;
	    private $m_nYear;
	    
	    public function getMonth()
	    {
	    	return $this->m_nMonth;
	    }
	    public function getYear()
	    {
	    	return $this->m_nYear;
	    }
	    
	    //constructor
	    public function __construct(NullableInt $nMonth = null, NullableInt $nYear = null)
	    {
	    	$this->m_nMonth = $nMonth;
	    	$this->m_nYear = $nYear;
	    }
	}

	class CardDetails
	{
		private $m_szCardName;
	    private $m_szCardNumber;
	    private $m_ccdExpiryDate;
	    private $m_ccdStartDate;
	    private $m_szIssueNumber;
	    private $m_szCV2;
	    
	    public function getCardName()
	    {
	    	return $this->m_szCardName;
	    }
	    public function getCardNumber()
	    {
	    	return $this->m_szCardNumber;
	    }
	    
	    public function getExpiryDate()
	    {
	    	return $this->m_ccdExpiryDate;
	    }
	   
	    public function getStartDate()
	    {
	    	return $this->m_ccdStartDate;
	    }
	    
	    public function getIssueNumber()
	    {
	    	return $this->m_szIssueNumber;
	    }
	    
	    public function getCV2()
	    {
	    	return $this->m_szCV2;
	    }
	    
	    //constructor
	    public function __construct($szCardName, $szCardNumber, CreditCardDate $ccdExpiryDate = null, CreditCardDate $ccdStartDate = null, $IssueNumber, $CV2)
	    {
	    	$this->m_szCardName = $szCardName;
	    	$this->m_szCardNumber = $szCardNumber;
	    	$this->m_ccdExpiryDate = $ccdExpiryDate;
	    	$this->m_ccdStartDate = $ccdStartDate;
	    	$this->m_szIssueNumber = $IssueNumber;
	    	$this->m_szCV2 = $CV2;
	    }
	}

	class MerchantDetails
	{
		private $m_szMerchantID;
	    private $m_szPassword;

	    public function getMerchantID()
	    {
	    	return $this->m_szMerchantID;
	    }
	    public function getPassword()
	    {
	    	return $this->m_szPassword;
	    }
	    
	    //constructor
	    public function __construct($szMerchantID, $szPassword)
	    {
	    	$this->m_szMerchantID = $szMerchantID;
	    	$this->m_szPassword = $szPassword;
	    }
	}

	class MessageDetails
	{
		private $m_ttTransactionType;
	    private $m_boNewTransaction;
	    private $m_szCrossReference;

	    public function getTransactionType()
	    {
	    	return $this->m_ttTransactionType;
	    }
	    public function getNewTransaction()
	    {
	    	return $this->m_boNewTransaction;
	    }
	    public function getCrossReference()
	    {
	    	return $this->m_szCrossReference;
	    }
	    
	    //constructor
	    public function __construct($ttTransactionType, $szCrossReference = null, NullableBool $boNewTransaction = null)
	    {
	    	$this->m_ttTransactionType = $ttTransactionType;
	    	
	    	if ($szCrossReference != null)
	    	{
	    		$this->m_szCrossReference = $szCrossReference;
	    	}
	    	if ($boNewTransaction != null)
	    	{
	    		$this->m_boNewTransaction = $boNewTransaction;
	    	}
	    }
	}

	class TransactionDetails
	{
		private $m_mdMessageDetails;
	    private $m_nAmount;
	    private $m_nCurrencyCode;
	    private $m_szOrderID;
	    private $m_szOrderDescription;
	    private $m_tcTransactionControl;
	    private $m_tdsbdThreeDSecureBrowserDetails;
	    
	    public function getMessageDetails()
	    {
	    	return $this->m_mdMessageDetails;
	    }
	    public function getAmount()
	    {
	    	return $this->m_nAmount;
	    }
	    public function getCurrencyCode()
	    {
	    	return $this->m_nCurrencyCode;
	    }
	   	public function getOrderID()
	    {
	    	return $this->m_szOrderID;
	    }
	    public function getOrderDescription()
	    {
	    	return $this->m_szOrderDescription;
	    }
	    public function getTransactionControl()
	    {
	    	return $this->m_tcTransactionControl;
	    }
	    public function getThreeDSecureBrowserDetails()
	    {
	    	return $this->m_tdsbdThreeDSecureBrowserDetails;
	    }
	    
	    //constructor
	    public function __construct($TransactionTypeOrMessageDetails, NullableInt $nAmount = null, NullableInt $nCurrencyCode = null, $szOrderID, $szOrderDescription, TransactionControl $tcTransactionControl = null, ThreeDSecureBrowserDetails $tdsbdThreeDSecureBrowserDetails = null)
	    {
			if ($TransactionTypeOrMessageDetails instanceof MessageDetails)
			{
				$this->m_mdMessageDetails = $TransactionTypeOrMessageDetails;
	    		$this->m_nAmount = $nAmount;
	    		$this->m_nCurrencyCode = $nCurrencyCode;
	    		$this->m_szOrderID = $szOrderID;
	    		$this->m_szOrderDescription = $szOrderDescription;
	    		$this->m_tcTransactionControl = $tcTransactionControl;
	    		$this->m_tdsbdThreeDSecureBrowserDetails = $tdsbdThreeDSecureBrowserDetails;
			}
			else
			{
				$this->__construct(new MessageDetails(new NullableTRANSACTION_TYPE($TransactionTypeOrMessageDetails)), $nAmount, $nCurrencyCode, $szOrderID, $szOrderDescription, $tcTransactionControl, $tdsbdThreeDSecureBrowserDetails);
			}
	    }
	}

	class ThreeDSecureBrowserDetails
	{
		private $m_nDeviceCategory;
	    private $m_szAcceptHeaders;
	    private $m_szUserAgent;

	    public function getDeviceCategory()
	    {
	    	return $this->m_nDeviceCategory;
	    }
	    
	    public function getAcceptHeaders()
	    {
	    	return $this->m_szAcceptHeaders;
	    }
	    
	    public function getUserAgent()
	    {
	    	return $this->m_szUserAgent;
	    }
	    
	    //constructor
	    public function __construct(NullableInt $nDeviceCategory = null, $szAcceptHeaders, $szUserAgent)
	    {
	    	$this->m_nDeviceCategory = $nDeviceCategory;
	    	$this->m_szAcceptHeaders = $szAcceptHeaders;
	    	$this->m_szUserAgent = $szUserAgent;	
	    }
	}
	    
	class TransactionControl
	{
		private $m_boEchoCardType;
	    private $m_boEchoAVSCheckResult;
	    private $m_boEchoCV2CheckResult;
	   	private $m_boEchoAmountReceived;
	    private $m_nDuplicateDelay;
	    private $m_szAVSOverridePolicy;
	    private $m_szCV2OverridePolicy;
	    private $m_boThreeDSecureOverridePolicy;
	    private $m_szAuthCode;
	    private $m_tdsptThreeDSecurePassthroughData;
	    private $m_lgvCustomVariables;
	    
	    public function getEchoCardType()
	    {
	    	return $this->m_boEchoCardType;
	    }
	   
	    public function getEchoAVSCheckResult()
	    {
	    	return $this->m_boEchoAVSCheckResult;
	    }
	    
	    public function getEchoCV2CheckResult()
	    {
	    	return $this->m_boEchoCV2CheckResult;
	    }
	    
	    public function getEchoAmountReceived()
	    {
	    	return $this->m_boEchoAmountReceived;
	    }
	   
	    public function getDuplicateDelay()
	    {
	    	return $this->m_nDuplicateDelay;
	    }
	    
	    public function getAVSOverridePolicy()
	    {
	    	return $this->m_szAVSOverridePolicy;
	    }
	    
	    public function getCV2OverridePolicy()
	    {
	    	return $this->m_szCV2OverridePolicy;
	    }
	    
	    public function getThreeDSecureOverridePolicy()
	    {
	    	return $this->m_boThreeDSecureOverridePolicy;
	    }
	    
	    public function getAuthCode()
	    {
	    	return $this->m_szAuthCode;
	    }
	    
	    function getThreeDSecurePassthroughData()
	    {
	    	return $this->m_tdsptThreeDSecurePassthroughData;
	    }
	   
	    public function getCustomVariables()
	    {
	    	return $this->m_lgvCustomVariables;
	    }
	    
	    //constructor
	    public function __construct(NullableBool $boEchoCardType = null, NullableBool $boEchoAVSCheckResult = null, NullableBool $boEchoCV2CheckResult = null, NullableBool $boEchoAmountReceived = null, NullableInt $nDuplicateDelay = null, $szAVSOverridePolicy, $szCV2OverridePolicy, NullableBool $boThreeDSecureOverridePolicy = null, $szAuthCode, ThreeDSecurePassthroughData $tdsptThreeDSecurePassthroughData = null, GenericVariableList $lgvCustomVariables = null)
	    {
	    	$this->m_boEchoCardType = $boEchoCardType;
	    	$this->m_boEchoAVSCheckResult = $boEchoAVSCheckResult;
	    	$this->m_boEchoCV2CheckResult = $boEchoCV2CheckResult;
	    	$this->m_boEchoAmountReceived = $boEchoAmountReceived;
	    	$this->m_nDuplicateDelay = $nDuplicateDelay;
	    	$this->m_szAVSOverridePolicy = $szAVSOverridePolicy;
	    	$this->m_szCV2OverridePolicy = $szCV2OverridePolicy;
	    	$this->m_boThreeDSecureOverridePolicy = $boThreeDSecureOverridePolicy;
	    	$this->m_szAuthCode = $szAuthCode;
	    	$this->m_tdsptThreeDSecurePassthroughData = $tdsptThreeDSecurePassthroughData;
	    	$this->m_lgvCustomVariables = $lgvCustomVariables;
	    }
	}

	class ThreeDSecureInputData
	{
		private $m_szCrossReference;
	    private $m_szPaRES;

	    public function getCrossReference()
	    {
	    	return $this->m_szCrossReference;
	    }
	    
	    public function getPaRES()
	    {
	    	return $this->m_szPaRES;
	    }
	   
	    //constructor
	    public function __construct($szCrossReference, $szPaRES)
	    {
	    	$this->m_szCrossReference = $szCrossReference;
	    	$this->m_szPaRES = $szPaRES;
	    }
	}

	class ThreeDSecurePassthroughData
	{
	 	private $m_szEnrolmentStatus;
	    private $m_szAuthenticationStatus;
	    private $m_szElectronicCommerceIndicator;
	    private $m_szAuthenticationValue;
	    private $m_szTransactionIdentifier;

	    function getEnrolmentStatus()
	    {
	    	return $this->m_szEnrolmentStatus;
	    }
	    
	    function getAuthenticationStatus()
	    {
	    	return $this->m_szAuthenticationStatus;
	    }
	    
	    function getElectronicCommerceIndicator()
	    {
	    	return $this->m_szElectronicCommerceIndicator;
	    }
	    
	    function getAuthenticationValue()
	    {
	    	return $this->m_szAuthenticationValue;
	    }

	    function getTransactionIdentifier()
	    {
	    	return $this->m_szTransactionIdentifier;
	    }

	    //constructor
	    function __construct($szEnrolmentStatus,
	                    	 $szAuthenticationStatus,
	                         $szElectronicCommerceIndicator,
	                         $szAuthenticationValue,
	                         $szTransactionIdentifier)
	    {
	     	$this->m_szEnrolmentStatus = $szEnrolmentStatus;
	        $this->m_szAuthenticationStatus = $szAuthenticationStatus;
	        $this->m_szElectronicCommerceIndicator = $szElectronicCommerceIndicator;
	        $this->m_szAuthenticationValue = $szAuthenticationValue;
	        $this->m_szTransactionIdentifier = $szTransactionIdentifier;
	    }
	}


	/******************/
	/* Output classes */
	/******************/
	class Issuer
	{
		private $m_szIssuer;
		private $m_nISOCode;
		
		public function getValue()
		{
			return $this->m_szIssuer;
		}
		
		public function getISOCode()
		{
			return $this->m_nISOCode;
		}
		
		//constructor
	    public function __construct($szIssuer, $nISOCode)
	    {
	        $this->m_szIssuer = $szIssuer;
	        $this->m_nISOCode = $nISOCode;
	    }
	}
	
	class CardTypeData
	{
	    private $m_ctCardType;
	    //private $m_szIssuer;
	    private $m_iIssuer;
	    private $m_boLuhnCheckRequired;
	    private $m_cdsIssueNumberStatus;
	    private $m_cdsStartDateStatus;

	    public function getCardType()
	    {
	        return $this->m_ctCardType;
	    }
	   
	    //public function getIssuer()
	    //{
	    //    return $this->m_szIssuer;
	    //}
	    public function getIssuer()
	    {
	    	return $this->m_iIssuer;
	    }
	   
	    public function getLuhnCheckRequired()
	    {
	        return $this->m_boLuhnCheckRequired;
	    }
	    
	    public function getIssueNumberStatus()
	    {
	        return $this->m_cdsIssueNumberStatus;
	    }
	   
	    public function getStartDateStatus()
	    {
	        return $this->m_cdsStartDateStatus;
	    }
	    
	    //constructor
	    public function __construct($ctCardType = null, $iIssuer, NullableBool $boLuhnCheckRequired = null, $cdsIssueNumberStatus, $cdsStartDateStatus)
	    {
	        $this->m_ctCardType = $ctCardType;
	        //$this->m_szIssuer = $szIssuer;
	        $this->m_iIssuer = $iIssuer;
	        $this->m_boLuhnCheckRequired = $boLuhnCheckRequired;
	        $this->m_cdsIssueNumberStatus = $cdsIssueNumberStatus;
	        $this->m_cdsStartDateStatus = $cdsStartDateStatus;
	    }
	}

	class GatewayEntryPoint
	{
		private $m_szEntryPointURL;
	    private $m_nMetric;

	 	public function getEntryPointURL()
	 	{
	 		return $this->m_szEntryPointURL;
	 	}
	 	
	    public function getMetric()
	    {
	    	return $this->m_nMetric;
	    }

	    //constructor
	    public function __construct($szEntryPointURL, $nMetric)
	    {
			$this->m_szEntryPointURL = $szEntryPointURL;
			$this->m_nMetric = $nMetric;
	    }
	}

	class GatewayEntryPointList
	{
	    private $m_lgepGatewayEntryPoint;

	    public function getAt($nIndex)
	    {
	        if ($nIndex < 0 ||
		     	$nIndex >= count($this->m_lgepGatewayEntryPoint))
		     {
		  	 	throw new Exception("Array index out of bounds");
		     }
		
	        return $this->m_lgepGatewayEntryPoint[$nIndex];
	    }

	    public function getCount()
	    {
	        return count($this->m_lgepGatewayEntryPoint);
	    }

	    public function add($GatewayEntrypointOrEntrypointURL, $nMetric)
	    {
	    	return array_push($this->m_lgepGatewayEntryPoint, new GatewayEntryPoint($GatewayEntrypointOrEntrypointURL, $nMetric));
	    }
	    
	    //constructor
	    public function __construct()
	    {
	       $this->m_lgepGatewayEntryPoint = array();	
	    }
	}

	class PreviousTransactionResult
	{
		private $m_nStatusCode;
	    private $m_szMessage;
	    //private $m_szCrossReference;
	    
	    function getStatusCode()
	    {
	    	return $this->m_nStatusCode;
	    }
	    
	    function getMessage()
	    {
	    	return $this->m_szMessage;
	    }
	    
	//    function getCrossReference()
	//    {
	//    	return $this->m_szCrossReference;
	//    }
	    
	    function __construct(NullableInt $nStatusCode = null,
	    						$szMessage
	    						/*$szCrossReference = null*/)
	    {
	    	$this->m_nStatusCode = $nStatusCode;
	    	//$this->m_szCrossReference = $szCrossReference;
	    	$this->m_szMessage = $szMessage;
	    }
	}

	class GatewayOutput
	{
	    private $m_nStatusCode;
	    private $m_szMessage;
	    private $m_szPassOutData;
	    private $m_ptdPreviousTransactionResult;
	    private $m_boAuthorisationAttempted;
	    private $m_lszErrorMessages;

	    public function getStatusCode()
	    {
	        return $this->m_nStatusCode;
	    }
	    
	    public function  getMessage()
	    {
	        return $this->m_szMessage;
	    }
	    
	    public function  getPassOutData()
	    {
	        return $this->m_szPassOutData;
	    }
	   
	    public function  getPreviousTransactionResult()
	    {
	        return $this->m_ptdPreviousTransactionResult;
	    }
	    
	    public function  getAuthorisationAttempted()
	    {
	        return $this->m_boAuthorisationAttempted;
	    }
	    
	    public function  getErrorMessages()
	    {
	        return $this->m_lszErrorMessages;
	    }
	    
	    //constructor
	    public function __construct($nStatusCode, $szMessage, $szPassOutData, NullableBool $boAuthorisationAttempted = null, PreviousTransactionResult $ptdPreviousTransactionResult = null, StringList $lszErrorMessages = null)
	    {
		    $this->m_nStatusCode = $nStatusCode;
			$this->m_szMessage = $szMessage;
			$this->m_szPassOutData = $szPassOutData;
			$this->m_boAuthorisationAttempted = $boAuthorisationAttempted;
			$this->m_ptdPreviousTransactionResult = $ptdPreviousTransactionResult;
			$this->m_lszErrorMessages = $lszErrorMessages;
	    }
	}

	class ThreeDSecureOutputData
	{
		private $m_szPaREQ;
	   	private $m_szACSURL;

	   	public function getPaREQ()
	   	{
			return $this->m_szPaREQ;
	   	}
	   
	   	public function getACSURL()
	   	{
	       	return ($this->m_szACSURL);
	   	}
	      
	   	//constructor
	   	public function __construct($szPaREQ, $szACSURL)
	   	{
			$this->m_szPaREQ = $szPaREQ;
	       	$this->m_szACSURL = $szACSURL;
	   	}
	}

	class GetGatewayEntryPointsOutputMessage extends BaseOutputMessage
	{
	   	//constructor
	   	function __construct(GatewayEntryPointList $lgepGatewayEntryPoints = null)
	   	{
	      	//BaseOutputMessage::__construct($lgepGatewayEntryPoints);
	      	parent::__construct($lgepGatewayEntryPoints);
	   	}
	}

	class TransactionOutputMessage extends BaseOutputMessage
	{
		private $m_szCrossReference;
		private $m_szAuthCode;
	    private $m_crAddressNumericCheckResult;
	    private $m_crPostCodeCheckResult;
	    private $m_crThreeDSecureAuthenticationCheckResult;
	    private $m_crCV2CheckResult;
	    private $m_ctdCardTypeData;
	    private $m_nAmountReceived;
	    private $m_tdsodThreeDSecureOutputData;
	    private $m_lgvCustomVariables;

	    public function getCrossReference()
	    { 
	        return $this->m_szCrossReference;
	    }
	    
	    public function getAuthCode()
	    { 
	        return $this->m_szAuthCode;
	    }

	    public function getAddressNumericCheckResult()
	    {
	       	return $this->m_crAddressNumericCheckResult;
	    }
	    
	    public function getPostCodeCheckResult()
	    { 
			return $this->m_crPostCodeCheckResult;
	    }
	    
	    public function getThreeDSecureAuthenticationCheckResult()
	    {
	        return $this->m_crThreeDSecureAuthenticationCheckResult;
	    }
	   
	    public function getCV2CheckResult()
	    {
	    	return $this->m_crCV2CheckResult;
	    }
	    
	    public function getCardTypeData()
	    {
	        return $this->m_ctdCardTypeData;
	    }
	   
	    public function getAmountReceived()
	    {
	       	return $this->m_nAmountReceived;
	    }
	    
	    public function getThreeDSecureOutputData()
	    {
	       	return $this->m_tdsodThreeDSecureOutputData;
	    }
	    
	    public function getCustomVariables()
	    {
	       	return $this->m_lgvCustomVariables;
	    }
	    
	 	//constructor
	    public function __construct($szCrossReference,
									$szAuthCode,
	    							NullableCHECK_RESULT $crAddressNumericCheckResult = null,
	    							NullableCHECK_RESULT $crPostCodeCheckResult = null,
	    							NullableCHECK_RESULT $crThreeDSecureAuthenticationCheckResult = null,
	    							NullableCHECK_RESULT $crCV2CheckResult = null,
	    							CardTypeData $ctdCardTypeData = null,
	    							NullableInt $nAmountReceived = null,
	    							ThreeDSecureOutputData $tdsodThreeDSecureOutputData = null,
	    							GenericVariableList $lgvCustomVariables = null,
	    							GatewayEntryPointList $lgepGatewayEntryPoints = null)
	    {
	     	//first calling the parent constructor
	        //BaseOutputMessage::__construct($lgepGatewayEntryPoints);
	        parent::__construct($lgepGatewayEntryPoints);
	        
		   	$this->m_szCrossReference = $szCrossReference;
			$this->m_szAuthCode = $szAuthCode;
			$this->m_crAddressNumericCheckResult = $crAddressNumericCheckResult;
			$this->m_crPostCodeCheckResult = $crPostCodeCheckResult;
			$this->m_crThreeDSecureAuthenticationCheckResult = $crThreeDSecureAuthenticationCheckResult;
			$this->m_crCV2CheckResult = $crCV2CheckResult;
			$this->m_ctdCardTypeData = $ctdCardTypeData;
			$this->m_nAmountReceived = $nAmountReceived;
			$this->m_tdsodThreeDSecureOutputData = $tdsodThreeDSecureOutputData;
			$this->m_lgvCustomVariables = $lgvCustomVariables;
	    }
	}

	class GetCardTypeOutputMessage extends BaseOutputMessage
	{
		private $m_ctdCardTypeData;

	   	public function getCardTypeData()
	   	{
	   		return $this->m_ctdCardTypeData;
	   	}

	  	//constructor
	   	public function __construct(CardTypeData $ctdCardTypeData,
	   								GatewayEntryPointList $lgepGatewayEntryPoints = null)
	   	{
	      	//BaseOutputMessage::__construct($lgepGatewayEntryPoints);
	      	parent::__construct($lgepGatewayEntryPoints);

	      	$this->m_ctdCardTypeData = $ctdCardTypeData;
	   	}
	}

	class BaseOutputMessage
	{
	   	private $m_lgepGatewayEntryPoints;

	   	public function getGatewayEntryPoints()
	   	{
	      	return $this->m_lgepGatewayEntryPoints;
	   	}

	   	//constructor
	   	public function __construct(GatewayEntryPointList $lgepGatewayEntryPoints = null)
	   	{
	      	$this->m_lgepGatewayEntryPoints = $lgepGatewayEntryPoints;
	   	}
	}


	/********************/
	/* Gateway messages */
	/********************/
	class GetGatewayEntryPoints extends GatewayTransaction
	{
	  	function processTransaction(GatewayOutput &$goGatewayOutput = null, GetGatewayEntryPointsOutputMessage &$ggepGetGatewayEntryPointsOutputMessage = null)
	   	{
	      	$boTransactionSubmitted = false;
	      	$sSOAPClient;
	      	$lgepGatewayEntryPoints;

	      	$ggepGetGatewayEntryPointsOutputMessage = null;
	      	$goGatewayOutput = null;

	      	$sSOAPClient = new SOAP('GetGatewayEntryPoints', GatewayTransaction::getSOAPNamespace());
	      	$boTransactionSubmitted = GatewayTransaction::processTransaction($sSOAPClient, 'GetGatewayEntryPointsMessage', 'GetGatewayEntryPointsResult', 'GetGatewayEntryPointsOutputData', $sxXmlDocument, $goGatewayOutput, $lgepGatewayEntryPoints);
	      
	      	if ($boTransactionSubmitted)
	      	{
	      		$ggepGetGatewayEntryPointsOutputMessage = new GetGatewayEntryPointsOutputMessage($lgepGatewayEntryPoints);
	      	}
	      
	      	return $boTransactionSubmitted;
	   	}
	   
	   	//constructor
	   	public function __construct(RequestGatewayEntryPointList $lrgepRequestGatewayEntryPoints = null,
	   								$nRetryAttempts,
	   								NullableInt $nTimeout = null,
	   								MerchantDetails $mdMerchantAuthentication = null,
	   								$szPassOutData)
	 	{
	   		if ($nRetryAttempts == null &&
	   			$nTimeout == null)
	   		{
	   			GatewayTransaction::__construct($lrgepRequestGatewayEntryPoints, 1, null, $mdMerchantAuthentication, $szPassOutData);								
	   		}
	   		else 
	   		{
	   			GatewayTransaction::__construct($lrgepRequestGatewayEntryPoints, $nRetryAttempts, $nTimeout, $mdMerchantAuthentication, $szPassOutData);
	   		}
	   	}
	}


	class CardDetailsTransaction extends GatewayTransaction 
	{
		private $m_tdTransactionDetails;
	    private $m_cdCardDetails;
	    private $m_cdCustomerDetails;
	     
	    public function getTransactionDetails()
	    {
	    	return $this->m_tdTransactionDetails;
	   	}
	     
	    public function getCardDetails()
	    {
	     	return $this->m_cdCardDetails;	
	    }
	     
	   	public function getCustomerDetails()
	    {
	    	return $this->m_cdCardDetails;
	    }
	     
	   	public function processTransaction(GatewayOutput &$goGatewayOutput = null, TransactionOutputMessage &$tomTransactionOutputMessage = null)
	   	{
	     	$boTransactionSubmitted = false;
	        $sSOAPClient;
	        $lgepGatewayEntryPoints = null;
	        $XmlDocument;

	      	$tomTransactionOutputMessage = null;
	        $goGatewayOutput = null;

	        $sSOAPClient = new SOAP('CardDetailsTransaction', parent::getSOAPNamespace());
	        
	    	// transaction details
	       	if ($this->m_tdTransactionDetails != null)
	        {
	        	$test = $this->m_tdTransactionDetails->getAmount();
	       		if ($this->m_tdTransactionDetails->getAmount() != null)
	          	{
	            	if ($this->m_tdTransactionDetails->getAmount()->getHasValue())
	                {
	                	$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails', 'Amount', (string)$this->m_tdTransactionDetails->getAmount()->getValue());
	                }
	            }
	            if ($this->m_tdTransactionDetails->getCurrencyCode() != null)
	          	{
	            	if ($this->m_tdTransactionDetails->getCurrencyCode()->getHasValue())
	                {
	                	$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails', 'CurrencyCode', (string)$this->m_tdTransactionDetails->getCurrencyCode()->getValue());
	                }
	            }
	            if ($this->m_tdTransactionDetails->getMessageDetails() != null)
	            {
	            	if ($this->m_tdTransactionDetails->getMessageDetails()->getTransactionType() != null)
	                {
	                    if ($this->m_tdTransactionDetails->getMessageDetails()->getTransactionType()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.MessageDetails', 'TransactionType', SharedFunctionsPaymentSystemShared::getTransactionType($this->m_tdTransactionDetails->getMessageDetails()->getTransactionType()->getValue()));
	                   	}
	                }
	            }
	            if ($this->m_tdTransactionDetails->getTransactionControl() != null)
	           	{
	             	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getAuthCode()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.AuthCode', $this->m_tdTransactionDetails->getTransactionControl()->getAuthCode());
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecureOverridePolicy() != null)
	                {
	                	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.ThreeDSecureOverridePolicy', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecureOverridePolicy()->getValue()));
	               	}
	               	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getAVSOverridePolicy()))
	                {
	                	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.AVSOverridePolicy', $this->m_tdTransactionDetails->getTransactionControl()->getAVSOverridePolicy());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getCV2OverridePolicy()))
	                {
	                	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.CV2OverridePolicy', ($this->m_tdTransactionDetails->getTransactionControl()->getCV2OverridePolicy()));
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getDuplicateDelay() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getDuplicateDelay()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.DuplicateDelay', (string)$this->m_tdTransactionDetails->getTransactionControl()->getDuplicateDelay()->getValue());
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCardType() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCardType()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoCardType', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoCardType()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoAVSCheckResult', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getValue()));
	                  	}
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoAVSCheckResult', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCV2CheckResult() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCV2CheckResult()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoCV2CheckResult', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoCV2CheckResult()->getValue()));
	                    }
	               	}
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAmountReceived() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAmountReceived()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoAmountReceived', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoAmountReceived()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData() != null)
	                {
	                	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getEnrolmentStatus()))
	                	{
	                		$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.TransactionControl.ThreeDSecurePassthroughData', 'EnrolmentStatus', $this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getEnrolmentStatus());
	                	}
	                	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getAuthenticationStatus()))
	                	{
	                		$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.TransactionControl.ThreeDSecurePassthroughData', 'AuthenticationStatus', $this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getAuthenticationStatus());
	                	}
	                	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getElectronicCommerceIndicator()))
	                	{
	                		$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.ThreeDSecurePassthroughData.ElectronicCommerceIndicator', $this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getElectronicCommerceIndicator());
	                	}
	                	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getAuthenticationValue()))
	                	{
	                		$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.ThreeDSecurePassthroughData.AuthenticationValue', $this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getAuthenticationValue());
	                	}
	                	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getTransactionIdentifier()))
	                	{
	                		$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.ThreeDSecurePassthroughData.TransactionIdentifier', $this->m_tdTransactionDetails->getTransactionControl()->getThreeDSecurePassthroughData()->getTransactionIdentifier());
	                	}
	                }
	          	}
	          	if ($this->m_tdTransactionDetails->getThreeDSecureBrowserDetails() != null)
	            {
	            	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getAcceptHeaders()))
	                {
	                	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.ThreeDSecureBrowserDetails.AcceptHeaders', $this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getAcceptHeaders());
	                }
	                if ($this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getDeviceCategory() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getDeviceCategory()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.ThreeDSecureBrowserDetails', 'DeviceCategory', (string)$this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getDeviceCategory()->getValue());
	                    }
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getUserAgent()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.TransactionDetails.ThreeDSecureBrowserDetails.UserAgent', $this->m_tdTransactionDetails->getThreeDSecureBrowserDetails()->getUserAgent());
	                }
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getOrderID()))
	           	{
	             	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.OrderID', $this->m_tdTransactionDetails->getOrderID());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getOrderDescription()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.TransactionDetails.OrderDescription', $this->m_tdTransactionDetails->getOrderDescription());
	            }
	        }
	        // card details
	        if ($this->m_cdCardDetails != null)
	        {
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCardDetails->getCardName()))
	            {
	            	$sSOAPClient->addParam('PaymentMessage.CardDetails.CardName', $this->m_cdCardDetails->getCardName());
	            }
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCardDetails->getCV2()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.CardDetails.CV2', $this->m_cdCardDetails->getCV2());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCardDetails->getCardNumber()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.CardDetails.CardNumber', $this->m_cdCardDetails->getCardNumber());
	            }
	            if ($this->m_cdCardDetails->getExpiryDate() != null)
	            {
	                if ($this->m_cdCardDetails->getExpiryDate()->getMonth() != null)
	                {
	                	if ($this->m_cdCardDetails->getExpiryDate()->getMonth()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.CardDetails.ExpiryDate', 'Month', (string)$this->m_cdCardDetails->getExpiryDate()->getMonth()->getValue());
	                    }
	                }
	                if ($this->m_cdCardDetails->getExpiryDate()->getYear() != null)
	                {
	                    if ($this->m_cdCardDetails->getExpiryDate()->getYear()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.CardDetails.ExpiryDate', 'Year', (string)$this->m_cdCardDetails->getExpiryDate()->getYear()->getValue());
	                    }
	               	}
	            }
	            if ($this->m_cdCardDetails->getStartDate() != null)
	            {
	                if ($this->m_cdCardDetails->getStartDate()->getMonth() != null)
	                {
	                	if ($this->m_cdCardDetails->getStartDate()->getMonth()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.CardDetails.StartDate', 'Month', (string)$this->m_cdCardDetails->getStartDate()->getMonth()->getValue());
	                    }
	                }
	                if ($this->m_cdCardDetails->getStartDate()->getYear() != null)
	                {
	                    if ($this->m_cdCardDetails->getStartDate()->getYear()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.CardDetails.StartDate', 'Year', (string)$this->m_cdCardDetails->getStartDate()->getYear()->getValue());
	                    }
	                }
	            }
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCardDetails->getIssueNumber()))
	            {
	               	$sSOAPClient->addParam('PaymentMessage.CardDetails.IssueNumber', $this->m_cdCardDetails->getIssueNumber());
	            }
	        }
	        // customer details
	        if ($this->m_cdCustomerDetails != null)
	        {
	        	if ($this->m_cdCustomerDetails->getBillingAddress() != null)
	            {
	             	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress1()))
	                {
	                	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address1', $this->m_cdCustomerDetails->getBillingAddress()->getAddress1());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress2()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address2', $this->m_cdCustomerDetails->getBillingAddress()->getAddress2());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress3()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address3', $this->m_cdCustomerDetails->getBillingAddress()->getAddress3());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress4()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address4', $this->m_cdCustomerDetails->getBillingAddress()->getAddress4());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getCity()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.City', $this->m_cdCustomerDetails->getBillingAddress()->getCity());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getState()))
	                {
	                  	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.State', $this->m_cdCustomerDetails->getBillingAddress()->getState());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getPostCode()))
	                {
	                   	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.PostCode', $this->m_cdCustomerDetails->getBillingAddress()->getPostCode());
	                }
	                if ($this->m_cdCustomerDetails->getBillingAddress()->getCountryCode() != null)
	                {
	                  	if ($this->m_cdCustomerDetails->getBillingAddress()->getCountryCode()->getHasValue())
	                    {
	                   		$sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.CountryCode', (string)$this->m_cdCustomerDetails->getBillingAddress()->getCountryCode()->getValue());
	                    }
	                }
	      		}
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getEmailAddress()))
	            {
	            	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.EmailAddress', $this->m_cdCustomerDetails->getEmailAddress());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getPhoneNumber()))
	            {
	              	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.PhoneNumber', $this->m_cdCustomerDetails->getPhoneNumber());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getCustomerIPAddress()))
	            {
	            	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.CustomerIPAddress', $this->m_cdCustomerDetails->getCustomerIPAddress());
	            }
	       	}
	       	
	       	$boTransactionSubmitted = GatewayTransaction::processTransaction($sSOAPClient, 'PaymentMessage', 'CardDetailsTransactionResult', 'TransactionOutputData', $XmlDocument, $goGatewayOutput, $lgepGatewayEntryPoints);

			if ($boTransactionSubmitted)
			{
				$tomTransactionOutputMessage = SharedFunctionsPaymentSystemShared::getTransactionOutputMessage($XmlDocument, $lgepGatewayEntryPoints);
			}

			return ($boTransactionSubmitted);
		}
	     
		public function __construct(RequestGatewayEntryPointList $lrgepRequestGatewayEntryPoints = null,
	     								$nRetryAttempts,
	     								NullableInt $nTimeout = null,
	     								MerchantDetails $mdMerchantAuthentication = null,
	                                    TransactionDetails $tdTransactionDetails = null,
	                                    CardDetails $cdCardDetails = null,
	                                    CustomerDetails $cdCustomerDetails = null,
	                                    $szPassOutData)
	  	{
	    	parent::__construct($lrgepRequestGatewayEntryPoints, $nRetryAttempts, $nTimeout, $mdMerchantAuthentication, $szPassOutData);
	        	
	        $this->m_tdTransactionDetails = $tdTransactionDetails;
	        $this->m_cdCardDetails = $cdCardDetails;
	        $this->m_cdCustomerDetails = $cdCustomerDetails;
	    }
	     
	}
	class CrossReferenceTransaction extends GatewayTransaction 
	{
		private $m_tdTransactionDetails;
	    private $m_cdOverrideCardDetails;
	    private $m_cdCustomerDetails;

	    public function getTransactionDetails()
	    {
			return $this->m_tdTransactionDetails;
	    }
	    public function getOverrideCardDetails()
	    {
	    	return $this->m_cdOverrideCardDetails;
	    }
	    public function getCustomerDetails()
	    {
	    	return $this->m_cdCustomerDetails;
	    }
	        
	    public function processTransaction(GatewayOutput &$goGatewayOutput = null, TransactionOutputMessage &$tomTransactionOutputMessage = null)
	    {
	    	$boTransactionSubmitted = false;
	        $sSOAPClient;
	        $lgepGatewayEntryPoints = null;
	        $sxXmlDocument = null;

	        $tomTransactionOutputMessage = null;
	        $goGatewayOutput = null;

	        $sSOAPClient = new SOAP('CrossReferenceTransaction', GatewayTransaction::getSOAPNamespace());
	      	// transaction details
	        if ($this->m_tdTransactionDetails != null)
	        {
	        	if ($this->m_tdTransactionDetails->getAmount() != null)
	          	{
	             	if ($this->m_tdTransactionDetails->getAmount()->getHasValue())
	                {
	               		$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails', 'Amount', (string)$this->m_tdTransactionDetails->getAmount()->getValue());
	                }
	            }
	            if ($this->m_tdTransactionDetails->getCurrencyCode() != null)
	            {
	                if ($this->m_tdTransactionDetails->getCurrencyCode()->getHasValue())
	                {
	                    $sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails', 'CurrencyCode', (string)$this->m_tdTransactionDetails->getCurrencyCode()->getValue());
	                }
	            }
	            if ($this->m_tdTransactionDetails->getMessageDetails() != null)
	            {
	                if ($this->m_tdTransactionDetails->getMessageDetails()->getTransactionType() != null)
	                {
	                    if ($this->m_tdTransactionDetails->getMessageDetails()->getTransactionType()->getHasValue())
	                    {
	                     	$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.MessageDetails', 'TransactionType', SharedFunctionsPaymentSystemShared::getTransactionType($this->m_tdTransactionDetails->getMessageDetails()->getTransactionType()->getValue()));
	                    }
	            	}
	                if ($this->m_tdTransactionDetails->getMessageDetails()->getNewTransaction() != null)
	                {
	                    if ($this->m_tdTransactionDetails->getMessageDetails()->getNewTransaction()->getHasValue())
	                    {
	                        $sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.MessageDetails', 'NewTransaction', SharedFunctions::boolToString($this->m_tdTransactionDetails->getMessageDetails()->getNewTransaction()->getValue()));
	                    }
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getMessageDetails()->getCrossReference()))
	                {
	                	$sSOAPClient->addParamAttribute('PaymentMessage.TransactionDetails.MessageDetails', 'CrossReference', $this->m_tdTransactionDetails->getMessageDetails()->getCrossReference());
	                }
	           	}
	           	if ($this->m_tdTransactionDetails->getTransactionControl() != null)
	           	{
	             	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getAuthCode()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.AuthCode', $this->m_tdTransactionDetails->getTransactionControl()->getAuthCode());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getAVSOverridePolicy()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.AVSOverridePolicy', $this->m_tdTransactionDetails->getTransactionControl()->getAVSOverridePolicy());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getTransactionControl()->getCV2OverridePolicy()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.CV2OverridePolicy', $this->m_tdTransactionDetails->getTransactionControl()->getCV2OverridePolicy());
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getDuplicateDelay() != null)
	                {
	                    if ($this->m_tdTransactionDetails->getTransactionControl()->getDuplicateDelay()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.DuplicateDelay', (string)($this->m_tdTransactionDetails->getTransactionControl()->getDuplicateDelay()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCardType() != null)
	                {
	                    if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCardType()->getHasValue())
	                    {
	                   		$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoCardType', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoCardType()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult() != null)
	                {
	                  	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoAVSCheckResult', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult() != null)
	                {
	                  	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoAVSCheckResult', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoAVSCheckResult()->getValue()));
	                    }
	                }
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCV2CheckResult() != null)
	                {
	                    if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoCV2CheckResult()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoCV2CheckResult', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoCV2CheckResult()->getValue()));
	                    }
	              	}
	                if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAmountReceived() != null)
	                {
	                	if ($this->m_tdTransactionDetails->getTransactionControl()->getEchoAmountReceived()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.TransactionDetails.TransactionControl.EchoAmountReceived', SharedFunctions::boolToString($this->m_tdTransactionDetails->getTransactionControl()->getEchoAmountReceived()->getValue()));
		               	}
	                }
	         	}
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getOrderID()))
	            {
	           		$sSOAPClient->addParam('PaymentMessage.TransactionDetails.OrderID', $this->m_tdTransactionDetails->getOrderID());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_tdTransactionDetails->getOrderDescription()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.TransactionDetails.OrderDescription', $this->m_tdTransactionDetails->getOrderDescription());
	            }
	        }
	        // card details
	       	if ($this->m_cdOverrideCardDetails != null)
	        {
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdOverrideCardDetails->getCardName()))
	            {
	            	$sSOAPClient->addParam('PaymentMessage.OverrideCardDetails.CardName', $this->m_cdOverrideCardDetails->getCardName());
	            }
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdOverrideCardDetails->getCV2()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.CardDetails.CV2', $this->m_cdOverrideCardDetails->getCV2());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdOverrideCardDetails->getCardNumber()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.OverrideCardDetails.CardNumber', $this->m_cdOverrideCardDetails->getCardNumber());
	            }
	            if ($this->m_cdOverrideCardDetails->getExpiryDate() != null)
	            {
	                if ($this->m_cdOverrideCardDetails->getExpiryDate()->getMonth() != null)
	                {
	                	if ($this->m_cdOverrideCardDetails->getExpiryDate()->getMonth()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.OverrideCardDetails.ExpiryDate', 'Month', (string)$this->m_cdOverrideCardDetails->getExpiryDate()->getMonth()->getValue());
	                    }
	                }
	                if ($this->m_cdOverrideCardDetails->getExpiryDate()->getYear() != null)
	                {
	                    if ($this->m_cdOverrideCardDetails->getExpiryDate()->getYear()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.OverrideCardDetails.ExpiryDate', 'Year', (string)$this->m_cdOverrideCardDetails->getExpiryDate()->getYear()->getValue());
	                    }
	                }
	            }
	            if ($this->m_cdOverrideCardDetails->getStartDate() != null)
	            {
	              	if ($this->m_cdOverrideCardDetails->getStartDate()->getMonth() != null)
	                {
	                	if ($this->m_cdOverrideCardDetails->getStartDate()->getMonth()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.OverrideCardDetails.StartDate', 'Month', (string)$this->m_cdOverrideCardDetails->getStartDate()->getMonth()->getValue());
	                    }
	                }
	                if ($this->m_cdOverrideCardDetails->getStartDate()->getYear() != null)
	                {
	                   	if ($this->m_cdOverrideCardDetails->getStartDate()->getYear()->getHasValue())
	                    {
	                    	$sSOAPClient->addParamAttribute('PaymentMessage.OverrideCardDetails.StartDate', 'Year', (string)$this->m_cdOverrideCardDetails->getStartDate()->getYear()->getValue());
	                    }
	                }
	            }
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdOverrideCardDetails->getIssueNumber()))
	            {
	               	$sSOAPClient->addParam('PaymentMessage.CardDetails.IssueNumber', $this->m_cdOverrideCardDetails->getIssueNumber());
	            }
	        }
	        // customer details
			if ($this->m_cdCustomerDetails != null)
	        {
	        	if ($this->m_cdCustomerDetails->getBillingAddress() != null)
	            {
	             	if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress1()))
	                {
	                	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address1', $this->m_cdCustomerDetails->getBillingAddress()->getAddress1());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress2()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address2', $this->m_cdCustomerDetails->getBillingAddress()->getAddress2());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress3()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address3', $this->m_cdCustomerDetails->getBillingAddress()->getAddress3());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getAddress4()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.Address4', $this->m_cdCustomerDetails->getBillingAddress()->getAddress4());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getCity()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.City', $this->m_cdCustomerDetails->getBillingAddress()->getCity());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getState()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.State', $this->m_cdCustomerDetails->getBillingAddress()->getState());
	                }
	                if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getBillingAddress()->getPostCode()))
	                {
	                    $sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.PostCode', (string)$this->m_cdCustomerDetails->getBillingAddress()->getPostCode());
	                }
	                if ($this->m_cdCustomerDetails->getBillingAddress()->getCountryCode() != null)
	                {
	                    if ($this->m_cdCustomerDetails->getBillingAddress()->getCountryCode()->getHasValue())
	                    {
	                    	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.BillingAddress.CountryCode', (string)$this->m_cdCustomerDetails->getBillingAddress()->getCountryCode()->getValue());
	                    }
	                }
	         	}
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getEmailAddress()))
	            {
	            	$sSOAPClient->addParam('PaymentMessage.CustomerDetails.EmailAddress', $this->m_cdCustomerDetails->getEmailAddress());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getPhoneNumber()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.CustomerDetails.PhoneNumber', $this->m_cdCustomerDetails->getPhoneNumber());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_cdCustomerDetails->getCustomerIPAddress()))
	            {
	                $sSOAPClient->addParam('PaymentMessage.CustomerDetails.CustomerIPAddress', $this->m_cdCustomerDetails->getCustomerIPAddress());
	            }
	        }
	        
	        $boTransactionSubmitted = GatewayTransaction::processTransaction($sSOAPClient, 'PaymentMessage', 'CrossReferenceTransactionResult', 'TransactionOutputData', $sxXmlDocument, $goGatewayOutput, $lgepGatewayEntryPoints);

	       	if ($boTransactionSubmitted)
	        {
	        	$tomTransactionOutputMessage = SharedFunctionsPaymentSystemShared::getTransactionOutputMessage($sxXmlDocument, $lgepGatewayEntryPoints);
	        }

	        return $boTransactionSubmitted;
	    }
	    
	    //constructor
	    public function __construct(RequestGatewayEntryPointList $lrgepRequestGatewayEntryPoints = null,
	    							$nRetryAttempts,
	    							NullableInt $nTimeout = null,
	    							MerchantDetails $mdMerchantAuthentication = null,
	    							TransactionDetails $tdTransactionDetails = null,
	    							CardDetails $cdOverrideCardDetails = null,
	    							CustomerDetails $cdCustomerDetails = null,
	    							$szPassOutData)
	    {
	    	GatewayTransaction::__construct($lrgepRequestGatewayEntryPoints, $nRetryAttempts, $nTimeout, $mdMerchantAuthentication, $szPassOutData);
		    	
		    $this->m_tdTransactionDetails = $tdTransactionDetails;
	      	$this->m_cdOverrideCardDetails = $cdOverrideCardDetails;
	       	$this->m_cdCustomerDetails = $cdCustomerDetails;
	    }
	}

	class ThreeDSecureAuthentication extends GatewayTransaction
	{
		private $m_tdsidThreeDSecureInputData;
		
		public function getThreeDSecureInputData()
		{
			return $this->m_tdsidThreeDSecureInputData;
		}
		
		public function processTransaction(GatewayOutput &$goGatewayOutput = null, TransactionOutputMessage &$tomTransactionOutputMessage = null)
		{
			$boTransactionSubmitted = false;
	        $sSOAPClient;
	        $lgepGatewayEntryPoints = null;
	        $sxXmlDocument = null;

	        $tomTransactionOutputMessage = null;
	        $goGatewayOutput = null;

	       	$sSOAPClient = new SOAP('ThreeDSecureAuthentication', GatewayTransaction::getSOAPNamespace());
	       	if ($this->m_tdsidThreeDSecureInputData != null)
	        {
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_tdsidThreeDSecureInputData->getCrossReference()))
	            {
	                $sSOAPClient->addParamAttribute('ThreeDSecureMessage.ThreeDSecureInputData', 'CrossReference', $this->m_tdsidThreeDSecureInputData->getCrossReference());
	            }
	            if (!SharedFunctions::isStringNullOrEmpty($this->m_tdsidThreeDSecureInputData->getPaRES()))
	            {
	            	$sSOAPClient->addParam('ThreeDSecureMessage.ThreeDSecureInputData.PaRES', $this->m_tdsidThreeDSecureInputData->getPaRES());
	            }
	        }
	        
	        $boTransactionSubmitted = GatewayTransaction::processTransaction($sSOAPClient, 'ThreeDSecureMessage', 'ThreeDSecureAuthenticationResult', 'TransactionOutputData', $sxXmlDocument, $goGatewayOutput, $lgepGatewayEntryPoints);
	       	
	        if ($boTransactionSubmitted)
	      	{
	        	$tomTransactionOutputMessage = SharedFunctionsPaymentSystemShared::getTransactionOutputMessage($sxXmlDocument, $lgepGatewayEntryPoints);
	        }

	        return $boTransactionSubmitted;
		}
		
		//constructor
		public function __construct(RequestGatewayEntryPointList $lrgepRequestGatewayEntryPoints = null,
									$nRetryAttempts,
									NullableInt $nTimeout = null,
									MerchantDetails $mdMerchantAuthentication = null,
	                              	ThreeDSecureInputData $tdsidThreeDSecureInputData = null,
	                                $szPassOutData)
	 	{
	    	GatewayTransaction::__construct($lrgepRequestGatewayEntryPoints, $nRetryAttempts, $nTimeout, $mdMerchantAuthentication, $szPassOutData);
	    	
	    	$this->m_tdsidThreeDSecureInputData = $tdsidThreeDSecureInputData;
	    }
	}

	class getCardType extends GatewayTransaction
	{
		private $m_szCardNumber;
		
		public function getCardNumber()
		{
			return $this->m_szCardNumber;
		}
		
		public function processTransaction(GatewayOutput &$goGatewayOutput = null, GetCardTypeOutputMessage &$gctomGetCardTypeOutputMessage = null)
		{
			$boTransactionSubmitted = false;
	        $sSOAPClient;
	       	$lgepGatewayEntryPoints = null;
	        $ctdCardTypeData = null;
	        $sxXmlDocument = null;

	       	$gctomGetCardTypeOutputMessage = null;
	        $goGatewayOutput = null;

	      	$sSOAPClient = new SOAP('GetCardType', GatewayTransaction::getSOAPNamespace());
	      	if (!SharedFunctions::isStringNullOrEmpty($this->m_szCardNumber))
	       	{
	        	$sSOAPClient->addParam('GetCardTypeMessage.CardNumber', $this->m_szCardNumber);
	        }
	        
	        $boTransactionSubmitted = GatewayTransaction::processTransaction($sSOAPClient, 'GetCardTypeMessage', 'GetCardTypeResult', 'GetCardTypeOutputData', $sxXmlDocument, $goGatewayOutput, $lgepGatewayEntryPoints);

	        if ($boTransactionSubmitted)
	        {
	        	if(!$sxXmlDocument->GetCardTypeOutputData->CardTypeData)
	        	{
	        		$ctdCardTypeData = null;
	        	}
	        	else
	        	{
	            	$ctdCardTypeData = SharedFunctionsPaymentSystemShared::getCardTypeData($sxXmlDocument->GetCardTypeOutputData->CardTypeData);
	        	}
	        	
	            if (!is_null($ctdCardTypeData)) 
	            {
	                $gctomGetCardTypeOutputMessage = new GetCardTypeOutputMessage($ctdCardTypeData, $lgepGatewayEntryPoints);
	            } 
			}
	        return $boTransactionSubmitted;
		}
		
		//constructor
		public function __construct(RequestGatewayEntryPointList $lrgepRequestGatewayEntryPoints = null,
									$nRetryAttempts,
	                           		NullableInt $nTimeout = null,
	                           		MerchantDetails $mdMerchantAuthentication = null,
	                           		$szCardNumber,
	                          		$szPassOutData)
	  	{
	    	GatewayTransaction::__construct($lrgepRequestGatewayEntryPoints, $nRetryAttempts, $nTimeout, $mdMerchantAuthentication, $szPassOutData);

	    	$this->m_szCardNumber = $szCardNumber;	
	    }
	}

	abstract class GatewayTransaction
	{
	    private $m_mdMerchantAuthentication;
	 	private $m_szPassOutData;
	    private $m_lrgepRequestGatewayEntryPoints;
	    private $m_nRetryAttempts;
	    private $m_nTimeout;
	    private $m_szSOAPNamespace = 'https://www.thepaymentgateway.net/';
	    private $m_szLastRequest;
		private $m_szLastResponse;
		private $m_eLastException;

	   	public function getMerchantAuthentication()
	   	{
	      	return $this->m_mdMerchantAuthentication;
	   	}
	  
	  	public function getPassOutData()
	   	{
	    	return $this->m_szPassOutData;
	  	}
	   
	   	public function getRequestGatewayEntryPoints()
	   	{
	    	return $this->m_lrgepRequestGatewayEntryPoints;
	   	}
	   
	   	public function getRetryAttempts()
	   	{
	      	return $this->m_nRetryAttempts;
	   	}
	   
	   	public function getTimeout()
	   	{
	      	return $this->m_nTimeout;
	   	}
	   
	   	public function getSOAPNamespace()
	   	{
	      	return $this->m_szSOAPNamespace;
	   	}
	   	public function setSOAPNamespace($value)
	   	{
	      	$this->m_szSOAPNamespace = $value;
	   	}
	   	
	   	public function getLastRequest()
	   	{
	   		return $this->m_szLastRequest;
	   	}
	   	
	   	public function getLastResponse()
	   	{
	   		return $this->m_szLastResponse;
	   	}
	   	
	   	public function getLastException()
	   	{
	   		return $this->m_eLastException;
	   	}

	   	public static function compare($x, $y)
	   	{
	      	$rgepFirst = null;
	      	$rgepSecond = null;
	     
	      	$rgepFirst = $x;
	      	$rgepSecond = $y;

	      	return (GatewayTransaction::compareGatewayEntryPoints($rgepFirst, $rgepSecond));
	   	}

	   	private static function compareGatewayEntryPoints(RequestGatewayEntryPoint $rgepFirst, RequestGatewayEntryPoint $rgepSecond)
	   	{
			$nReturnValue = 0;
	      	// returns >0 if rgepFirst greater than rgepSecond
	      	// returns 0 if they are equal
	      	// returns <0 if rgepFirst less than rgepSecond
	      
	      	// both null, then they are the same
	      	if ($rgepFirst == null &&
	          	$rgepSecond == null)
	   		{
	        	$nReturnValue = 0;
	        }
	      	// just first null? then second is greater
	      	elseif ($rgepFirst == null &&
		    		$rgepSecond != null)
	      	{
	        	$nReturnValue = 1;
	        }
	      	// just second null? then first is greater
	      	elseif ($rgepFirst != null  && $rgepSecond == null)
	      	{
	        	$nReturnValue = -1;
	        }
	      	// can now assume that first & second both have a value
	      	elseif ($rgepFirst->getMetric() == $rgepSecond->getMetric())
	        {
	        	$nReturnValue = 0;
	        }
	      	elseif ($rgepFirst->getMetric() < $rgepSecond->getMetric())
	        {
	        	$nReturnValue = -1;
	        }
	      	elseif ($rgepFirst->getMetric() > $rgepSecond->getMetric())
		    {
				$nReturnValue = 1;
	  	    }

	      	return $nReturnValue;
	   	}

	   	protected function processTransaction(SOAP $sSOAPClient, $szMessageXMLPath, $szGatewayOutputXMLPath, $szTransactionMessageXMLPath, SimpleXMLElement &$sxXmlDocument = null, GatewayOutput &$goGatewayOutput = null, GatewayEntryPointList &$lgepGatewayEntryPoints = null)
	   	{
			$boTransactionSubmitted = false;
		    $nOverallRetryCount = 0;
		    $nOverallGatewayEntryPointCount = 0;
		    $nGatewayEntryPointCount = 0;
		    $nErrorMessageCount = 0;
		    $rgepCurrentGatewayEntryPoint;
		    $nStatusCode;
		    $szMessage = null;
		    $lszErrorMessages;
		    $szString;
		    $sbXMLString;
		    $szXMLFormatString;
		    $nCount = 0;
		    $szEntryPointURL;
		    $nMetric;
		    $nTempValue = 0;
		    $gepGatewayEntryPoint = null;
		    $boAuthorisationAttempted = null;
		    $boTempValue;
		    $szPassOutData = null;
		    //$szPreviousCrossReference = null;
		    $nPreviousStatusCode = null;
		    $szPreviousMessage = null;
		    $ptdPreviousTransactionResult = null;
		    $ResponseDocument = null;
		    $ResponseMethod = null;

	      	$lgepGatewayEntryPoints = null;
	      	$goGatewayOutput = null;

	      	if ($sSOAPClient == null)
	      	{
	        	return false;
	      	}

	       	// populate the merchant details
	       	if ($this->m_mdMerchantAuthentication != null)
	       	{
	        	if (!SharedFunctions::isStringNullOrEmpty($this->m_mdMerchantAuthentication->getMerchantID()))
	          	{
	            	$sSOAPClient->addParamAttribute($szMessageXMLPath. '.MerchantAuthentication', 'MerchantID', $this->m_mdMerchantAuthentication->getMerchantID());
	          	}
	          	if (!SharedFunctions::isStringNullOrEmpty($this->m_mdMerchantAuthentication->getPassword()))
	          	{
	             	$sSOAPClient->addParamAttribute($szMessageXMLPath. '.MerchantAuthentication', 'Password', $this->m_mdMerchantAuthentication->getPassword());
	          	}
	       	}
	       	// populate the passout data
	       	if (!SharedFunctions::isStringNullOrEmpty($this->m_szPassOutData))
	       	{
	        	$sSOAPClient->addParam($szMessageXMLPath. '.PassOutData', $this->m_szPassOutData, null);
	       	}

	      	// first need to sort the gateway entry points into the correct usage order
	       	$number = $this->m_lrgepRequestGatewayEntryPoints->sort('GatewayTransaction','Compare');
	       
	       	// loop over the overall number of transaction attempts
	       	while (!$boTransactionSubmitted &&
	       			$nOverallRetryCount < $this->m_nRetryAttempts) 
	       	{
	       		$nOverallGatewayEntryPointCount = 0;
	       			
	       		// loop over the number of gateway entry points in the list
	            while (!$boTransactionSubmitted &&
	                 	$nOverallGatewayEntryPointCount < $this->m_lrgepRequestGatewayEntryPoints->getCount())
	          	{
	       			
					$rgepCurrentGatewayEntryPoint = $this->m_lrgepRequestGatewayEntryPoints->getAt($nOverallGatewayEntryPointCount);
					
					// ignore if the metric is "-1" this indicates that the entry point is offline
	              	if ($rgepCurrentGatewayEntryPoint->getMetric() >= 0)
	                {
	              		$nGatewayEntryPointCount = 0;
	                 	$sSOAPClient->setURL($rgepCurrentGatewayEntryPoint->getEntryPointURL());
						
	                    // loop over the number of times to try this specific entry point
	                    while (!$boTransactionSubmitted &&
	                          	$nGatewayEntryPointCount < $rgepCurrentGatewayEntryPoint->getRetryAttempts())
	                  	{
	                    	if ($sSOAPClient->sendRequest($ResponseDocument, $ResponseMethod))
	                        {
	                        	//getting the valid transaction type document format
	                        	$sxXmlDocument = $ResponseDocument->$ResponseMethod;
	                        	
	                        	$lszErrorMessages = new StringList();
	                        	
								$nStatusCode = (int)current($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->StatusCode[0]);

								// a status code of 50 means that this entry point is not to be used
								if ($nStatusCode != 50)
								{
		                        	// the transaction was submitted
		                        	$boTransactionSubmitted = true;

									if ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->Message)
									{
										$szMessage = current($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->Message[0]);
									}
									if ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->ErrorMessages)
									{
										foreach ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->ErrorMessages->MessageDetail as $key => $value)
										{
											$lszErrorMessages->add(current($value->Detail));
 										}
									}
									
									if ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->attributes())
									{
										foreach ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->attributes() as $key => $value)
										{
											$boAuthorisationAttempted = current($value);
											if (strtolower($boAuthorisationAttempted) == 'false')
											{
												$boAuthorisationAttempted = new NullableBool(false);
											}
											elseif (strtolower($boAuthorisationAttempted) == 'true')
											{
												$boAuthorisationAttempted = new NullableBool(true);
											}
											else 
											{
												throw new Exception('Return value must be true or false');
											}
										}
									}
									
									if ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->PassOutData)
									{
										$szPassOutData = current($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->PassOutData[0]);
									}
									else 
									{
										$szPassOutData = null;
									}
									
									//check to see if there is any previous transaction data
									if ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->PreviousTransactionResult->StatusCode)
									{
										$nPreviousStatusCode = new NullableInt(current($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->PreviousTransactionResult->StatusCode[0]));
									}
									else 
									{
										$nPreviousStatusCode = null;
									}
									if ($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->PreviousTransactionResult->Message)
									{
										$szPreviousMessage = current($ResponseDocument->$ResponseMethod->$szGatewayOutputXMLPath->PreviousTransactionResult->Message[0]);
									}
									
									if ($nPreviousStatusCode != null &&
										!SharedFunctions::isStringNullOrEmpty($szPreviousMessage))
									{
										$ptdPreviousTransactionResult = new PreviousTransactionResult($nPreviousStatusCode, $szPreviousMessage);		
									}
									
									$goGatewayOutput = new GatewayOutput($nStatusCode, $szMessage, $szPassOutData, $boAuthorisationAttempted, $ptdPreviousTransactionResult, $lszErrorMessages);
		                                
		                            // look to see if there are any gateway entry points
		                            $nCount = 0;
		                            
		                            //$szXMLFormatString = $ResponseDocument->$ResponseMethod->$szTransactionMessageXMLPath->GatewayEntryPoints->GatewayEntryPoint;
		                            
		                            $nMetric = -1;
		                            
		                            if ($ResponseDocument->$ResponseMethod->$szTransactionMessageXMLPath->GatewayEntryPoints)
		                            {
		                            	if($ResponseDocument->$ResponseMethod->$szTransactionMessageXMLPath->GatewayEntryPoints->GatewayEntryPoint)
		                            	{
			                            	$szXMLFormatString = $ResponseDocument->$ResponseMethod->$szTransactionMessageXMLPath->GatewayEntryPoints->GatewayEntryPoint;
			                            	
					                      	foreach($szXMLFormatString->attributes() as $key => $value)
					                        {
					                          	if (is_numeric(current($value)))
					                           	{
					                           		$nMetric = current($value);
					                           	}
					                           	else 
					                           	{
					                           		$szEntryPointURL = current($value);
					                           	}
					                       	}
				                            
				                            //$gepGatewayEntryPoint = new GatewayEntryPoint($szEntryPointURL, $nMetric);
				                            if ($lgepGatewayEntryPoints == null)
				                            {
				                            	$lgepGatewayEntryPoints = new GatewayEntryPointList();
				                            }
				                            $lgepGatewayEntryPoints->add($szEntryPointURL, $nMetric); //$lgepGatewayEntryPoints->add($gepGatewayEntryPoint);
		                            	}
		                            }
		                            $nCount++;
								}
	                    	}
	                            
	                        $nGatewayEntryPointCount++;
	                  	}
	              	}
	                $nOverallGatewayEntryPointCount++;
	       		}
	       		$nOverallRetryCount++;
	   		}
	   		$this->m_szLastRequest = $sSOAPClient->getSOAPPacket();
	   		$this->m_szLastResponse = $sSOAPClient->getLastResponse();
	   		$this->m_eLastException = $sSOAPClient->getLastException();

	   		return $boTransactionSubmitted;
		}
		
		public function __construct(RequestGatewayEntryPointList $lrgepRequestGatewayEntryPoints = null,
									$nRetryAttempts,
									NullableInt $nTimeout = null,
									MerchantDetails $mdMerchantAuthentication = null,
									$szPassOutData)
		{
			$this->m_mdMerchantAuthentication = $mdMerchantAuthentication;
			$this->m_szPassOutData = $szPassOutData;
			$this->m_lrgepRequestGatewayEntryPoints = $lrgepRequestGatewayEntryPoints;
			$this->m_nRetryAttempts = $nRetryAttempts;
			$this->m_nTimeout = $nTimeout;
		}
	}

	class SharedFunctionsPaymentSystemShared
	{
		public static function getTransactionOutputMessage(SimpleXMLElement $sxXmlDocument, GatewayEntryPointList $lgepGatewayEntryPoints = null)
		{
			$szCrossReference = null;
	        $crAddressNumericCheckResult = null;
	        $crPostCodeCheckResult = null;
	        $crThreeDSecureAuthenticationCheckResult = null;
	        $crCV2CheckResult = null;
	        $szAddressNumericCheckResult = null;
	        $szPostCodeCheckResult = null;
	        $szThreeDSecureAuthenticationCheckResult = null;
	        $szCV2CheckResult = null;
	        $nAmountReceived = null;
	        $szPaREQ = null;
	        $szACSURL = null;
	        $nTempValue;
	        $ctdCardTypeData = null;
	        $tdsodThreeDSecureOutputData = null;
	        $lgvCustomVariables = null;
	        $nCount = 0;
	        $sbString;
	        $szXMLFormatString;
	        $szName;
	        $szValue;
	        $gvGenericVariable;
	        $nCount = 0;
	        $szCardTypeData;
	        
	        $tomTransactionOutputMessage = null;

			if (!$sxXmlDocument->TransactionOutputData)
			{
				return (null);
			}

		    if ($sxXmlDocument->TransactionOutputData->attributes())
		    {
		    	foreach($sxXmlDocument->TransactionOutputData->attributes() as $key => $value)
		    	{
		    		$szCrossReference = current($value);
		    	}
		    }
		    else 
		    {
		    	$szCrossReference = null;
		    }

			if ($sxXmlDocument->TransactionOutputData->AuthCode)
			{
				$szAuthCode = current($sxXmlDocument->TransactionOutputData->AuthCode[0]);
			}
			else
			{
				$szAuthCode = null;
			}

			if ($sxXmlDocument->TransactionOutputData->AddressNumericCheckResult)
			{
				$crAddressNumericCheckResult = new NullableCHECK_RESULT(current($sxXmlDocument->TransactionOutputData->AddressNumericCheckResult[0]));
			}
			else
			{
				$crAddressNumericCheckResult = new NullableCHECK_RESULT(null);
			}
			
			if ($sxXmlDocument->TransactionOutputData->PostCodeCheckResult)
			{
		    	$crPostCodeCheckResult = new NullableCHECK_RESULT(current($sxXmlDocument->TransactionOutputData->PostCodeCheckResult[0]));
			}
			else 
			{
				$crPostCodeCheckResult = new NullableCHECK_RESULT(null);
			}
		    
		    if ($sxXmlDocument->TransactionOutputData->ThreeDSecureAuthenticationCheckResult)
		    {
				$crThreeDSecureAuthenticationCheckResult = new NullableCHECK_RESULT(current($sxXmlDocument->TransactionOutputData->ThreeDSecureAuthenticationCheckResult[0]));
		    }
		    else 
		    {
		    	$crThreeDSecureAuthenticationCheckResult = new NullableCHECK_RESULT(null);
		    }

			if ($sxXmlDocument->TransactionOutputData->CV2CheckResult)
			{
		    	$crCV2CheckResult = new NullableCHECK_RESULT(current($sxXmlDocument->TransactionOutputData->CV2CheckResult[0]));
			}
			else 
			{
				$crCV2CheckResult = new NullableCHECK_RESULT(null);
			}
		    
		    if ($sxXmlDocument->TransactionOutputData->CardTypeData)
		    {
		    	$ctdCardTypeData = self::getCardTypeData($sxXmlDocument->TransactionOutputData->CardTypeData);
		    }
		    else 
		    {
		    	$ctdCardTypeData = null;
		    }

			if ($sxXmlDocument->TransactionOutputData->AmountReceived)
			{
		    	$nAmountReceived = new NullableInt(current($sxXmlDocument->TransactionOutputData->AmountReceived[0]));
			}
			else 
			{
				$nAmountReceived = new NullableInt(null);
			}

			if ($sxXmlDocument->TransactionOutputData->ThreeDSecureOutputData)
			{
				$szPaREQ = current($sxXmlDocument->TransactionOutputData->ThreeDSecureOutputData->PaREQ[0]);
				$szACSURL = current($sxXmlDocument->TransactionOutputData->ThreeDSecureOutputData->ACSURL[0]);
			}
			else 
			{
				$szPaREQ = null;
				$szACSURL = null;
			}
			

		    if (!SharedFunctions::isStringNullOrEmpty($szACSURL) &&
		    	!SharedFunctions::isStringNullOrEmpty($szPaREQ))
		    {
		    	$tdsodThreeDSecureOutputData = new ThreeDSecureOutputData($szPaREQ, $szACSURL);
		    }
		        
			if ($sxXmlDocument->TransactionOutputData->CustomVariables->GenericVariable)
			{
				if ($lgvCustomVariables == null)
				{
					$lgvCustomVariables = new GenericVariableList();
				}
				for ($nCount=0; $nCount < count($sxXmlDocument->TransactionOutputData->CustomVariables->GenericVariable); $nCount++)
				{
					$szName = current($sxXmlDocument->TransactionOutputData->CustomVariables->GenericVariable[$nCount]->Name[0]);
					$szValue = current($sxXmlDocument->TransactionOutputData->CustomVariables->GenericVariable[$nCount]->Value[0]);
					$gvGenericVariable = new GenericVariable($szName, $szValue);
					$lgvCustomVariables->add($gvGenericVariable);
				}
			}
			else 
			{
				$lgvCustomVariables = null;
			}


		    $tomTransactionOutputMessage = new TransactionOutputMessage($szCrossReference,
																		$szAuthCode,
															         	$crAddressNumericCheckResult,
															            $crPostCodeCheckResult,
															            $crThreeDSecureAuthenticationCheckResult,
															            $crCV2CheckResult,
															            $ctdCardTypeData,
															            $nAmountReceived,
															            $tdsodThreeDSecureOutputData,
															            $lgvCustomVariables,
															            $lgepGatewayEntryPoints);
			
	     	return $tomTransactionOutputMessage;
		}

		public static function getCardTypeData($CardTypeDataTag)
		{
			$ctdCardTypeData = null;
	        $nTempValue;
	        $boTempValue;
	        $ctCardType;
	        $boLuhnCheckRequired = null;
	        $cdsStartDateStatus = null;
	        $cdsIssueNumberStatus = null;
	        $szCardType;
	        $szIssuer = null;
	        $nISOCode = null;
	        $iIssuer;

			if ($CardTypeDataTag->CardType)
			{
				$ctCardType = self::getCardType(current($CardTypeDataTag->CardType[0]));
			}
			else 
			{
				$ctCardType = null;
			}
			
			if ($CardTypeDataTag->Issuer)
			{
				try 
				{
					$szIssuer = (string) $CardTypeDataTag->Issuer[0];
				} 
				catch (Exception $e) 
				{
					$szIssuer = null;
				}
				
				try
				{
					$nISOCode = current($CardTypeDataTag->Issuer->attributes()->ISOCode);
				}
				catch (Exception $e)
				{
					$nISOCode = null;
				}
				
				$iIssuer = new Issuer($szIssuer, $nISOCode);
			}
			else 
			{
				//$szIssuer = null;
				$iIssuer = null;
			}
			
			if ($CardTypeDataTag->LuhnCheckRequired)
			{
				$boLuhnCheckRequired = new NullableBool(current($CardTypeDataTag->LuhnCheckRequired[0]));
			}
			else 
			{
				$boLuhnCheckRequired = null;
			}
			
			if ($CardTypeDataTag->IssueNumberStatus)
			{
				try 
				{
					$cdsIssueNumberStatus = self::getCardDataStatus(current($CardTypeDataTag->IssueNumberStatus[0])); //new NullableInt(current($sxXmlDocument->CardTypeData->IssueNumberStatus[0]));	
				} 
				catch (Exception $e) 
				{
					$cdsIssueNumberStatus = null;
				}
			}
			else 
			{
				$cdsIssueNumberStatus = null;
			}
			
			if ($CardTypeDataTag->StartDateStatus)
			{
				try 
				{
					$cdsStartDateStatus = self::getCardDataStatus(current($CardTypeDataTag->StartDateStatus[0])); //new NullableInt(current($sxXmlDocument->CardTypeData->StartDateStatus[0]));
				} 
				catch (Exception $e) 
				{
					$cdsStartDateStatus = null;
				}
			}
			else 
			{
				$cdsStartDateStatus = null;
			}
			
			$ctdCardTypeData = new CardTypeData($ctCardType, $iIssuer, $boLuhnCheckRequired, $cdsIssueNumberStatus, $cdsStartDateStatus);

	        return ($ctdCardTypeData);
		}
		
		public static function getCardType($CardType)
		{
			if ($CardType instanceof CARD_TYPE)
			{
				return (string)$CardType;
			}
			elseif (is_string($CardType))
			{
				$ctCardType = CARD_TYPE::UNKNOWN;
				
				if ($CardType == null ||
					!is_string($CardType))
		       	{
		         	throw new Exception('Invalid transaction type: ' . $CardType);
		        }
		        if (strtoupper($CardType) == 'AMERICAN_EXPRESS')
		      	{
		        	$ctCardType = CARD_TYPE::AMERICAN_EXPRESS;
		        }
		       	elseif (strtoupper($CardType) == 'DINERS_CLUB')
		      	{
		        	$ctCardType = CARD_TYPE::DINERS_CLUB;
		        }
		        elseif (strtoupper($CardType) == 'JCB')
		      	{
		        	$ctCardType = CARD_TYPE::JCB;
		        }
		        elseif (strtoupper($CardType) == 'ATM')
		      	{
		        	$ctCardType = CARD_TYPE::ATM;
		        }
		        elseif (strtoupper($CardType) == 'MASTERCARD')
		      	{
		        	$ctCardType = CARD_TYPE::MASTERCARD;
		        }
		        elseif (strtoupper($CardType) == 'SOLO')
		      	{
		        	$ctCardType = CARD_TYPE::SOLO;
		        }
		        elseif (strtoupper($CardType) == 'PLATIMA')
		      	{
		        	$ctCardType = CARD_TYPE::PLATIMA;
		        }
		        elseif (strtoupper($CardType) == 'VISA_ELECTRON')
		      	{
		        	$ctCardType = CARD_TYPE::VISA_ELECTRON;
		        }
		        elseif (strtoupper($CardType) == 'MAESTRO')
		      	{
		        	$ctCardType = CARD_TYPE::MAESTRO;
		        }
		        elseif (strtoupper($CardType) == 'VISA')
		      	{
		        	$ctCardType = CARD_TYPE::VISA;
		        }
		        elseif (strtoupper($CardType) == 'VISA_DEBIT')
		      	{
		        	$ctCardType = CARD_TYPE::VISA_DEBIT;
		        }
		        elseif (strtoupper($CardType) == 'VISA_PURCHASING')
		      	{
		        	$ctCardType = CARD_TYPE::VISA_PURCHASING;
		        }
		        elseif (strtoupper($CardType) == 'GE_CAPITAL')
		      	{
		        	$ctCardType = CARD_TYPE::GE_CAPITAL;
		        }
		        elseif (strtoupper($CardType) == 'LASER')
		      	{
		        	$ctCardType = CARD_TYPE::LASER;
		        }
		        
		        return $ctCardType;
			}
			else 
			{
				throw new Exception('Invalid parameter type' . $CardType);
			}
		}
		public static function getCheckResult($CheckResult)
		{
			if ($CheckResult instanceof CHECK_RESULT)
			{
				return (string)$CheckResult;
			}
			elseif (is_string($CheckResult))
			{
				$crCheckResult = CHECK_RESULT::UNKNOWN;
				
				if ($CheckResult == null ||
					!is_string($CheckResult))
		       	{
		         	throw new Exception('Invalid transaction type: ' . $CheckResult);
		        }
		        
		       	if (strtoupper($CheckResult) == 'FAILED')
		      	{
		        	$crCheckResult = CHECK_RESULT::FAILED;
		        }
		       	elseif (strtoupper($CheckResult) == 'PASSED')
		      	{
		        	$crCheckResult = CHECK_RESULT::PASSED;
		        }
		        elseif (strtoupper($CheckResult) == 'PARTIAL')
		      	{
		        	$crCheckResult = CHECK_RESULT::PARTIAL;
		        }
		        elseif (strtoupper($CheckResult) == 'ERROR')
		      	{
		        	$crCheckResult = CHECK_RESULT::ERROR;
		        }
		        elseif (strtoupper($CheckResult) == 'NOT_CHECKED')
		      	{
		        	$crCheckResult = CHECK_RESULT::NOT_CHECKED;
		        }
		        elseif (strtoupper($CheckResult) == 'NOT_SUBMITTED')
		      	{
		        	$crCheckResult = CHECK_RESULT::NOT_SUBMITTED;
		        }
		        elseif (strtoupper($CheckResult) == 'NOT_ENROLLED')
		      	{
		        	$crCheckResult = CHECK_RESULT::NOT_ENROLLED;
		        }
		        
		        return $crCheckResult;
			}
			else 
			{
				throw new Exception('Invalid parameter type' . $CheckResult);
			}
	        
		}
		public static function getTransactionType($TransactionType)
		{
			if ($TransactionType instanceof TRANSACTION_TYPE)
			{
				return (string)$TransactionType;
			}
			elseif (is_string($TransactionType))
			{
				$ttTransactionType = TRANSACTION_TYPE::UNKNOWN;
				
				if ($TransactionType == null ||
					!is_string($TransactionType))
		       	{
		         	throw new Exception('Invalid transaction type: ' . $TransactionType);
		        }
		
		       	if (strtoupper($TransactionType) == 'COLLECTION')
		      	{
		        	$ttTransactionType = TRANSACTION_TYPE::COLLECTION;
		        }
		       	elseif (strtoupper($TransactionType) == 'PREAUTH')
		      	{
		        	$ttTransactionType = TRANSACTION_TYPE::PREAUTH;
		        }
		        elseif (strtoupper($TransactionType) == 'REFUND')
		      	{
		        	$ttTransactionType = TRANSACTION_TYPE::REFUND;
		        }
		        elseif (strtoupper($TransactionType) == 'RETRY')
		      	{
		        	$ttTransactionType = TRANSACTION_TYPE::RETRY;
		        }
		        elseif (strtoupper($TransactionType) == 'SALE')
		      	{
		        	$ttTransactionType = TRANSACTION_TYPE::SALE;
		        }
		        elseif (strtoupper($TransactionType) == 'VOID')
		      	{
		        	$ttTransactionType = TRANSACTION_TYPE::VOID;
		        }
		        
		        if ($ttTransactionType == TRANSACTION_TYPE::UNKNOWN)
		       	{
		        	throw new Exception('Invalid transaction type: ' . $szTransactionType);
		        }
		        
		        return ($ttTransactionType);
			}
			else 
			{
				throw new Exception('Invalid parameter type' . $TransactionType);
			}
		}
		public static function getCardDataStatus($CardDataStatus)
		{
			if ($CardDataStatus instanceof CARD_DATA_STATUS)
			{
				return (string)$CardDataStatus;
			}
			elseif (is_string($CardDataStatus))
			{
				$cdsCardDataStatus = CARD_DATA_STATUS::UNKNOWN;
				
				if ($CardDataStatus == null ||
					!is_string($CardDataStatus))
	            {
	                throw new Exception("Invalid card data status: " + $CardDataStatus);
	            }
	            
	            if (strtoupper($CardDataStatus) == 'DO_NOT_SUBMIT')
		      	{
		        	$cdsCardDataStatus = CARD_DATA_STATUS::DO_NOT_SUBMIT;
		        }
		       	elseif (strtoupper($CardDataStatus) == 'IGNORED_IF_SUBMITTED')
		      	{
		        	$cdsCardDataStatus = CARD_DATA_STATUS::IGNORED_IF_SUBMITTED;
		        }
		        elseif (strtoupper($CardDataStatus) == 'MUST_BE_SUBMITTED')
		      	{
		        	$cdsCardDataStatus = CARD_DATA_STATUS::MUST_BE_SUBMITTED;
		        }
		        elseif (strtoupper($CardDataStatus) == 'SUBMIT_ONLY_IF_ON_CARD')
		      	{
		        	$cdsCardDataStatus = CARD_DATA_STATUS::SUBMIT_ONLY_IF_ON_CARD;
		        }
		        
		        return ($cdsCardDataStatus);
			}
		}
	}


	define('MODULE_PAYMENT_IRIDIUM_TEXT_TITLE', 'Iridium');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_DESCRIPTION', 'iridiumcorp.co.uk');
	define('MODULE_PAYMENT_IRIDIUM_CURRENCY', 'USD');
	define('STORE_NAME', 'A2Billing');
	define('MODULE_PAYMENT_IRIDIUM_LANGUAGE', 'EN');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_OWNER', 'Card owner:');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_NUMBER', 'Card number:');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_ISSUE_NUMBER', 'Issue number:');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_CVV', 'CVV/CVV2:');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_STARTS', 'Valid from:');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_EXPIRES', 'Expiry date:');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_CVV_LINK', 'What is it?');
	define('MODULE_PAYMENT_IRIDIUM_TEXT_ERROR', 'Credit Card Error!');
	
	



?>
