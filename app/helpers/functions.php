<?php 

/**
  * convert xml string to php array - useful to get a serializable value
  *
  * @param string $xmlstr
  * @return array
  *
  * @author Adrien aka Gaarf & contributors
  * @see http://gaarf.info/2009/08/13/xml-string-to-php-array/
*/
function xmlToArray($xmlstr) {
  $doc = new DOMDocument();
  $doc->loadXML($xmlstr);
  $root = $doc->documentElement;
  $output = domNodeToArray($root);
  $output['@root'] = $root->tagName;
  return $output;
}

/**
 * Comvert DOM to array
 * @param  [type] $node [description]
 * @return [type]       [description]
 */
function domNodeToArray($node) {
  $output = array();
  switch ($node->nodeType) {
    case XML_CDATA_SECTION_NODE:
    case XML_TEXT_NODE:
      $output = trim($node->textContent);
    break;
    case XML_ELEMENT_NODE:
      for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
        $child = $node->childNodes->item($i);
        $v = domNodeToArray($child);
        if(isset($child->tagName)) {
          $t = $child->tagName;
          if(!isset($output[$t])) {
            $output[$t] = array();
          }
          $output[$t][] = $v;
        }
        elseif($v || $v === '0') {
          $output = (string) $v;
        }
      }
      if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
        $output = array('@content'=>$output); //Change output into an array.
      }
      if(is_array($output)) {
        if($node->attributes->length) {
          $a = array();
          foreach($node->attributes as $attrName => $attrNode) {
            $a[$attrName] = (string) $attrNode->value;
          }
          $output['@attributes'] = $a;
        }
        foreach ($output as $t => $v) {
          if(is_array($v) && count($v)==1 && $t!='@attributes') {
            $output[$t] = $v[0];
          }
        }
      }
    break;
  }
  return $output;
}

/**
 * Get submited inputs
 * @return inputs
 */   
function getRawInput()
{
  return file_get_contents("php://input");
}