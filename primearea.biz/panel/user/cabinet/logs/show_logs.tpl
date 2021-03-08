<div id="spoiler-logs" class="spoiler" style="display:none;">
	<table class="table table-striped table_page">
		<tbody>
            {{FOR:authlog}}
			    <tr>
			        <td class="padding_b_10 padding_t_10">{authTs}</td>
			        <td class="padding_b_10 padding_t_10">{authIp} / {authCountryName}</td>
			        <td class="padding_b_10 padding_t_10">{authSystem}</td>
			    </tr>
			{{ENDFOR:authlog}}
		</tbody>
	</table>
</div>