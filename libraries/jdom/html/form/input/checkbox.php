<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormInputCheckbox extends JDomHtmlFormInput
{
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
	}

	function build()
	{
		$html = '';

		$checked = (!empty($this->dataValue));

		$html =	'<input type="checkbox" id="<%DOM_ID%>" name="<%INPUT_NAME%>"<%STYLE%><%CLASS%><%SELECTORS%>'
			.	' value="1"'
			.	($checked?' checked="checked"':'')
			.	'/>';

		return $html;
	}
}