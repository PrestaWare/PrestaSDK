{**
 * Prestashop Module Development Kit
 *
 * @author     Hashem Afkhami <hashemafkhami89@gmail.com>
 * @copyright  (c) 2025 - PrestaWare Team
 * @website    https://prestaware.com
 * @license    https://www.gnu.org/licenses/gpl-3.0.html [GNU General Public License]
 *}

{if isset($_positions.HEADER)}
	{$_positions.HEADER|escape:'htmlall':'UTF-8'}
{/if}

<div id="wsdk-panel">
	{if isset($_positions.TOP_CONTAINER)}
		{$_positions.TOP_CONTAINER|escape:'htmlall':'UTF-8'}
	{/if}

	<div class="wsdk-panel-content">
		{if isset($_positions.SIDEBAR)}
			<div class="wsdk-panel-sidebar">
				{$_positions.SIDEBAR}
			</div>
		{/if}

		<div class="wsdk-panel-main">
			{if isset($_positions.TOP_CONTENT)}
				{$_positions.TOP_CONTENT}
			{/if}
			
			{if isset($_flash.message) && !empty($_flash.message)}
				<div class="row">
					<div class="col-sm-12">
						<div class="alert alert-{if isset($_flash.type)}{$_flash.type|escape:'htmlall':'UTF-8'}{else}info{/if} ">
							<button type="button" class="close" data-dismiss="alert">×</button>
							{$_flash.message}
						</div>
					</div>
				</div>
			{/if}
			
			{$_content}
			
			{if isset($_positions.BOTTOM_CONTENT)}
				{$_positions.BOTTOM_CONTENT}
			{/if}
			
		</div>
	</div>
	
	{if isset($_positions.BOTTOM_CONTAINER)}
		{$_positions.BOTTOM_CONTAINER}
	{/if}
</div>

{if isset($_positions.FOOTER)}
	{$_positions.FOOTER}
{/if}