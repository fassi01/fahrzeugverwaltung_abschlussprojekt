<?php 

use App\Libraries\SiteAuth;

?>
	    
        <?php if(session()->getFlashdata('msgOK')):?>
			<div class="messsageBox">
				<div class="messageBoxOk"><?= session()->getFlashdata('msgOK') ?></div>
			</div>                
        <?php endif;?>
        
        <?php if(session()->getFlashdata('msgWarning')):?>
			<div class="messsageBox">
				<div class="messageBoxWarning"><?= session()->getFlashdata('msgWarning') ?></div>
			</div>                
        <?php endif;?>            
    
        <?php if(session()->getFlashdata('msgError')):?>
			<div class="messsageBox">
				<div class="messageBoxError"><?= session()->getFlashdata('msgError') ?></div>
			</div>                
        <?php endif;?>        
                
            	
        <div class="baseHeader">
        	<div class="authGroupImg"><h1>&nbsp;&nbsp;&nbsp;Berechtigungsübersicht</h1></div>
        </div>
        
        
        <div class="mainView">
        	<div class="viewBodyUserAuthRoleList">
        		<div class="viewButtonsRight">
        			<?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
            		<form autocomplete="off" action="<?php echo base_url('UserAuthRole/insertButton');?>" method="post">
    					<input type="submit" value="Berechtigungsrolle anlegen" class="authRoleListCreateRoleButton">
    				</form>
    				<?php endif; ?>
    			</div>
            	
                <div class="searchOptionsBody">
                	<div class="searchOptionsHeader">
                    	<div class="searchOptionsHeaderText">
                    		Suchkriterien
                    	</div>
                    	<div class="searchOptionsHide <?php if (isset($_GET['modus'])){echo 'searchOptionsVisible';}else{echo 'searchOptionsInvisible';}?>">
                    		<?php if (isset($_GET['modus'])) : ?>
                    			<span class="searchOptionsHideSpan">Suchfelder ausblenden</span>
                    		<?php else : ?>
                    			<span class="searchOptionsHideSpan">Suchfelder einblenden</span>
                    		<?php endif; ?>
                    	</div>
                	</div>                	
                	
                	<div class="searchOptions">
                		
                		<form autocomplete="off" action="<?php echo base_url('UserAuthRoleList/search');?>" method="post">
                		
                    		<input class="searchOptionText" type="text" id="inpAuthRoleIdText" name="inpAuthRoleIdText" value="Berechtigungsrolle ID" readonly>
                            <select class="searchOptionOperator" name="inpAuthRoleIdOp">
                               	<option value="empty" <?php if (set_value("inpAuthRoleIdOp") == "empty"){echo "selected='selected'";}?>></option> 	
                                <option value="=" <?php if (set_value("inpAuthRoleIdOp") == "="){echo "selected='selected'";}?>>=</option>
                                <option value="&lt;&gt;" <?php if (set_value("inpAuthRoleIdOp") == "&lt;&gt;"){echo "selected='selected'";}?>>&lt;&gt;</option>
                                <option value="&gt;" <?php if (set_value("inpAuthRoleIdOp") == "&gt;"){echo "selected='selected'";}?>>&gt;</option>
                                <option value="&lt;" <?php if (set_value("inpAuthRoleIdOp") == "&lt;"){echo "selected='selected'";}?>>&lt;</option>
                            </select>                		
    						<input class="searchOptionValue" type="text" id="inpAuthRoleIdValue" name="inpAuthRoleIdValue" value="<?= set_value('inpAuthRoleIdValue');?>">
                    		</br>
                    		
                    		<input class="searchOptionText" type="text" id="inpAuthRoleDescText" name="inpAuthRoleDescText" value="Berechtigungsrolle" readonly>
                            <select class="searchOptionOperator" name="inpAuthRoleDescOp">
                               	<option value="empty" <?php if (set_value("inpAuthRoleDescOp") == "empty"){echo "selected='selected'";}?>></option> 	
                                <option value="=" <?php if (set_value("inpAuthRoleDescOp") == "="){echo "selected='selected'";}?>>=</option>
                                <option value="&lt;&gt;" <?php if (set_value("inpAuthRoleDescOp") == "&lt;&gt;"){echo "selected='selected'";}?>>&lt;&gt;</option>
                                <option value="CP" <?php if (set_value("inpAuthRoleDescOp") == "CP"){echo "selected='selected'";}?>>enthält</option>
                                <option value="SW" <?php if (set_value("inpAuthRoleDescOp") == "SW"){echo "selected='selected'";}?>>beginnt mit</option>
                            </select>
    						<input class="searchOptionValue" type="text" id="inpAuthRoleDescValue" name="inpAuthRoleDescValue" value="<?= set_value('inpAuthRoleDescValue');?>">                        
                            </br>
                            
                            <input type="submit" value="Suchen" class="searchOptionsButtonSearch">
                        </form>                        
                        <input type="submit" value="Zurücksetzen" class="searchOptionsButtonUndo">
                		
                	</div>
                	
                </div>                        
            
    			<div class="tableMenu">
    				<div class="maxRowsPerPage">
    					<label>Max. Einträge</label>
    					<select id="maxRows" class="maxRows">
    					  <option value="5">5</option>
    					  <option value="10" selected="selected">10</option>
    					  <option value="25">25</option>
    					  <option value="50">50</option>
    					  <option value="100">100</option>
    					</select>				
    				</div>
    				
    				<div class="totalEntriesFound"><?= count($tableView);?> Einträge gefunden</div>			
    				
    				<div class="quickSearch">
    					<input type="text" class ="quickSearchInput" id="quickSearchInput" name="quickSearchInput" placeholder="Schnellsuche">				
    				</div>
    			</div>            
                
                
    				<div class="tableView">
    					
    					<table class="styled-table styled-table-UserAuthRoleList">
    						<thead>
    							<tr>
    								<th><div class="thHeader" id="thHeader_0"><div class="thHeaderText">Berechtigungsrolle ID</div><div class="thImgSort"><img class="imgSort" src="/img/sort.png"></div></div></th>
    								<th><div class="thHeader" id="thHeader_1"><div class="thHeaderText">Berechtigungsrolle</div><div class="thImgSort"><img class="imgSort" src="/img/sort.png"></div></div></th>
    								<th>&nbsp;&nbsp;&nbsp;</th>
    							</tr>
    						</thead>
    						<tbody id="tableBody">
    						</tbody>
    					</table>			
    				
    				
    				</div>
    				
    				<div class="tablePager" id="tablePager">
    					<div class="tablePagerBody" id="tablePagerBody">
    					</div>					
    				</div>
    			</div>
			</div>                


<script>
	$(document).ready(function(){		
	
		// ***********************************************************************************************
		// Start-initialization
		// ***********************************************************************************************
		
		// Get tableView-Array from PHP-Controller		
		var jsonObj = [];				
		var table = <?php echo json_encode($tableView); ?>;
		console.log(table);
		
		for ( var i = 0, l = table.length; i < l; i++ ) {
			item = {};
			item ["g_id"] = table[i]['g_id'];
			item ["g_description"] = table[i]['g_description'];
			
			jsonObj.push(item);			
		}
		
		
		// write Json Objekt 'table' to html-table
		
		var row = "";
		var columnCounter = 0;
		var rowCounter = 0;
		
		$.each(jsonObj, function (key, val) {							
			
			$.removeData(row);
			columnCounter = 0;
			rowCounter = rowCounter + 1;
			
			
			$.each(val, function (key2, val2) {				
				
				columnCounter = columnCounter + 1;
						
				if (key2 == "g_id") {
					g_id = val2;
				}	
				
				if (columnCounter == 1){
					if (rowCounter % 2 > 0){
						// odd-ungerade row					
						row = "<tr class='tableTr tableTrOdd tableTrVisible' data-href='UserAuthRole/?modus=show&authRoleId=" + g_id + "' >";
					}else{
						// even-gerade row
						row = "<tr class='tableTr tableTrEven tableTrVisible' data-href='UserAuthRole/?modus=show&authRoleId=" + g_id + "' >";
					}
				}
			
				row = row + "<td>" + val2 + "</td>";
			});
			
			
			<?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
			row = row + "<td><a href='UserAuthRole/?modus=change&action=changeButton&authRoleId=" + g_id + "' title='Editieren'><img class='imgEdit' src='/img/edit.png' alt='Edit'></a></td>"
			<?php else : ?>
			row = row + "<td></td>"
			<?php endif; ?>			
			row = row + "</tr>";
			$("#tableBody").append(row);					
				
		});
		
		// set tablemenü-width dynamic to the html-table width
		
		var tableWidth = $(".styled-table").width();
		$(".styled-table").css({ minWidth: tableWidth });
		$(".tableMenu").width(tableWidth);
		
		// Show table-pager		
		
		showPager(1);
		updateTableView(1);
		
		// Show/hide SearchOptions
		
		if ($(".searchOptionsHide").hasClass( "searchOptionsVisible" )){
			$(".searchOptions").show();
		}else{
			$(".searchOptions").hide();
		}
		
		
		// ***********************************************************************************************
		// End-initialization
		// ***********************************************************************************************		
		
		
        $("#maxRows").change(function() {
        
        	showPager(1);
        	updateTableView(1);  
        	      	    		  	
        });
        
        
        $(document).on('click', '.divPager', function(){
          
            var currentPagerId = this.id.replace("pager_","");          
            var pagerTotal = getTotalPages();
            var currentPagerIdInt = parseInt(currentPagerId);
            showPager(currentPagerIdInt);  
            updateTableView(currentPagerIdInt);    
                
        });
        
        
        $(document).on('click', '.divPagerForward', function(){          	
          	
          	var currentPagerId = $(".divPagerActive")[0].id.replace("pager_","");
            var pagerTotal = getTotalPages();
            var currentPagerIdInt = parseInt(currentPagerId);
            
            if(currentPagerIdInt + 1 > pagerTotal){
            	//do nothing
            }else{
            	currentPagerIdInt = currentPagerIdInt + 1;
            }
            
            showPager(currentPagerIdInt);  
            updateTableView(currentPagerIdInt);    
                
        });
        
        
        $(document).on('click', '.divPagerForwardLast', function(){
          	
            var pagerTotal = getTotalPages();
            
            showPager(pagerTotal);  
            updateTableView(pagerTotal);    
                
        });
        
        
        $(document).on('click', '.divPagerBack', function(){          	
          	
          	var currentPagerId = $(".divPagerActive")[0].id.replace("pager_","");
            var pagerTotal = getTotalPages();
            var currentPagerIdInt = parseInt(currentPagerId);
            
            if(currentPagerIdInt - 1 < 1){
            	//do nothing
            }else{
            	currentPagerIdInt = currentPagerIdInt - 1;
            }
            
            showPager(currentPagerIdInt);  
            updateTableView(currentPagerIdInt);    
                
        });
        
        $(document).on('click', '.divPagerBackFirst', function(){  
        
            showPager(1);  
            updateTableView(1);    
                
        });                           
        
		function getTableLength(){
			
			var counter = 0;
			
            $(".tableTr").each(function () {												
				
				if ($(this).hasClass( "tableTrVisible" )){
					counter = counter + 1
				}
				
			});
			
			return counter;			
		}        
        
        function getTotalPages(){
        
    		var maxRows = $("#maxRows option:selected").text();
    		var tableLength = getTableLength();
    		var pagerTotal = 0;    		
    		
    		var divi = Math.floor(tableLength / maxRows);
    		var modulo = tableLength % maxRows;
    		
    		if (modulo > 0){
    			pagerTotal = divi + 1;
    		}else{
    			pagerTotal = divi;
    		}
    		
    		if (divi == 0 && modulo == 0){
    			pagerTotal = 1;
    		}
    		
    		return pagerTotal;
          
        }
        
        function updateTableView(currentPage){
        	
        	var maxRows = $("#maxRows option:selected").text();     	
        	var startRow = ((maxRows * currentPage) - maxRows) + 1;
        	var endRow = maxRows * currentPage;
        	var rowCounter = 1; 
        	
			$(".tableTr").each(function () {
				
				if ($(this).hasClass( "tableTrVisible" )){
    				if(rowCounter >= startRow && rowCounter <= endRow){
    					$(this).show();
    				}else{
    					$(this).hide();
    				}
    				
    				rowCounter = rowCounter + 1;
				}
			});
			
			   
			     	
        }        
        
		function showPager(currentPage) {
    		var maxRows = $("#maxRows option:selected").text();    		
    		var tableLength = getTableLength();
    		var pagerTotal = 0;
    		var pagerHtml = '';
    		var pagerCounter = 0;
    		var endPage = 0;
    		var startPage = 0;
    		var checkEndPages = 0;
    		var checkStartPages = 0;
    		var marginPages = 2;
    		var showMoreBeginning = '';
    		var showMoreEnding = '';    		
    		
    		
    		var divi = Math.floor(tableLength / maxRows);
    		var modulo = tableLength % maxRows;
    		
    		if (modulo > 0){
    			pagerTotal = divi + 1;
    		}else{
    			pagerTotal = divi;
    		}
    		
    		if (divi == 0 && modulo == 0){
    			pagerTotal = 1;
    		}
    		
    		if (currentPage == 1){
        		if (pagerTotal > 5){
        			endPage = 5;
        			startPage = 1;        			
        		}else{
        			endPage = pagerTotal;
        			startPage = 1;       			
        		}
    		}else{    			
    			
        		checkEndPages = currentPage + marginPages;
        		checkStartPages = currentPage - marginPages;
        		 
        		if (checkEndPages > pagerTotal ){
        			
        			if (currentPage == pagerTotal){
						endPage = pagerTotal;
        				startPage = currentPage - 4;        			
        			}else{
						endPage = pagerTotal;
        				startPage = currentPage - 3;        			
        			}   
        			

        		}else{
        			
        			endPage = currentPage + marginPages;
        			startPage = currentPage - marginPages;
        		}
        		
        		// it is what it is
        		if (checkStartPages < 1 && pagerTotal > 5){
        			endPage = 5;
        			startPage = 1;        			
				}
				
        		if (checkStartPages <= 1 && pagerTotal < 5){
        			endPage = pagerTotal;
        			startPage = 1;        			
				}
				
        		if (pagerTotal <= 5){
        			endPage = pagerTotal;
        			startPage = 1;        			
				}								
    		
    		}
    		
    		// show morePages '...' beginning and ending
    		
    		if(currentPage <= 5 && pagerTotal <= 5){
        			showMoreBeginning = '';
        			showMoreEnding = '';    		
    		}    		
    		
    		if(currentPage <= 3 && pagerTotal > 5){
        			showMoreBeginning = '';
        			showMoreEnding = 'X';    		
    		}    		
    		
    		if(currentPage > 3 && pagerTotal > 5){
        			showMoreBeginning = 'X';
        			showMoreEnding = 'X';    		
    		}
    		
    		
    		if(currentPage == pagerTotal && pagerTotal > 5){
        			showMoreBeginning = 'X';
        			showMoreEnding = '';    		
    		}
    		
    		if(currentPage + 2 >= pagerTotal && pagerTotal > 5){
        			showMoreBeginning = 'X';
        			showMoreEnding = '';    		
    		}    		     		    		
    		 		
    		
    		pagerHtml = "<div class='tablePagerBody' id='tablePagerBody'>";
    		pagerHtml = pagerHtml + "<div class='divPagerBackFirst'>&lt;&lt;</div>";
    		pagerHtml = pagerHtml + "<div class='divPagerBack'>&lt;</div>";
    		
    		if (showMoreBeginning == 'X'){
    			pagerHtml = pagerHtml + "<div class='divPagerMore'>...</div>";
    		}
    		
    		
    		for ( var i = startPage, l = endPage; i <= l; i++ ) {
    				
    			pagerCounter = i;
    			
    			//if (pagerCounter == 1){
    			if (pagerCounter == currentPage){
    				pagerHtml = pagerHtml + "<div class='divPagerActive' ";
    				pagerHtml = pagerHtml + "id='pager_";
    				pagerHtml = pagerHtml + pagerCounter;
    				pagerHtml = pagerHtml + "'>";
    			}else{    				
    				pagerHtml = pagerHtml + "<div class='divPager' ";
    				pagerHtml = pagerHtml + "id='pager_";
    				pagerHtml = pagerHtml + pagerCounter;
    				pagerHtml = pagerHtml + "'>";
    			}
    			
    			pagerHtml = pagerHtml + pagerCounter;
    			pagerHtml = pagerHtml + "</div>";	
    			
    		}
    		
    		if (showMoreEnding == 'X'){
    			pagerHtml = pagerHtml + "<div class='divPagerMore'>...</div>";
    		}
    		
    		pagerHtml = pagerHtml + "<div class='divPagerForward'>&gt;</div>";
    		pagerHtml = pagerHtml + "<div class='divPagerForwardLast'>&gt;&gt;</div>";
    		pagerHtml = pagerHtml + "</div>";
    		
    		$("#tablePagerBody").remove();
    		$("#tablePager").append(pagerHtml);
        					
		}
				
		
		// Quicksearch / Schnellsuche		
				
		$("#quickSearchInput").keyup(function () {
			var value = this.value.toLowerCase().trim();
			var rowCounter = 0;

			$(".tableTr").each(function () {												
				$(this).find("td").each(function () {			
					
					var tdText = $(this).text().toLowerCase().trim();
					
					if (tdText.indexOf(value) == -1){
						$(this).closest('tr').hide();
						$(this).closest('tr').removeClass();
						$(this).closest('tr').addClass("tableTr");
					}else{
					
						rowCounter = rowCounter + 1;
						$(this).closest('tr').show();
						
						if (rowCounter % 2 > 0){
							// odd-ungerade row
							$(this).closest('tr').removeClass();
							$(this).closest('tr').addClass("tableTr tableTrOdd tableTrVisible");
						}else{
							// even-gerade row
							$(this).closest('tr').removeClass();
							$(this).closest('tr').addClass("tableTr tableTrEven tableTrVisible");							
						}
						
						return false;
					}
				});
			});
			
			showPager(1);  
            updateTableView(1); 
			
		});

		
        $(document).on('mouseover', '.imgEdit', function(){          	
          	
          	$(this).attr("src","/img/editHover.gif");
                
        });
        
        
        $(document).on('mouseout', '.imgEdit', function(){          	
          	
          	$(this).attr("src","/img/edit.png");
                
        });
        
        
        $(document).on('click', '.tableTr', function(){

          	window.location = $(this).data("href");
                
        });
        
        
        $(document).on('mouseover', '.thHeader', function(){          	
          	
          	$(this).find('.thHeaderText').css({ 'text-decoration': 'underline' });
                
        });
        
        
        $(document).on('mouseout', '.thHeader', function(){          	
          	
          	$(this).find('.thHeaderText').css({ 'text-decoration': 'none' });
                
        });
        
        
        // Sortierung, beim Klick auf eine Spalte in der TableView
        
        $(document).on('click', '.thHeader', function(){
        	
        	// Sorting Columns		
			var foundSorting = false;
			var foundSortingAsc = false;
			var foundSortingDesc = false;
			
			
			if($(this).find('.imgSort').hasClass('imgSortAsc')){
				foundSorting = true;
				foundSortingAsc = true;
			}
			
			if($(this).find('.imgSort').hasClass('imgSortDesc')){
				foundSorting = true;
				foundSortingDesc = true;
			}
			
			$('.styled-table').find('.imgSort').each(function () {
				$(this).attr('src','/img/sort.png');
				$(this).removeClass();
				$(this).addClass('imgSort');
			});
			
			if(foundSorting){
				if(foundSortingAsc){
				
					// absteigend sortieren	
					$(this).find('.imgSort').attr('src','/img/sortDesc.png');
					$(this).find('.imgSort').addClass('imgSortDesc');					
					
            		var thIndex = this.id.replace('thHeader_',''); 
					sortTable('Desc', thIndex);
					
				}else{
					
					// aufsteigend sortieren (Initial-Soriterung)
					$(this).find('.imgSort').attr('src','/img/sort.png');            		 
					sortTable('Asc', 0);										
				}
				
			}else{
				
				// aufsteigend sortieren						
				$(this).find('.imgSort').attr('src','/img/sortAsc.png');
				$(this).find('.imgSort').addClass('imgSortAsc');
				
				var thIndex = this.id.replace('thHeader_','');
				sortTable('Asc', thIndex);
			}
			
			/*

			*/      	
                
        }); 
		
		function sortTable(sortType, thIndex) {
			
			var table = $('.styled-table');
			var rows = table.find('.tableTr').toArray().sort(comparer(thIndex));
			var rowCounter = 0;				
			
			if (sortType == 'Desc') {
				// absteigend sortieren	                
				rows = rows.reverse(); 
				
			}else{
				// aufsteigend sortieren
				
			}
			
    		$('#tableBody').find('tr').remove();
    		$('#tableBody').append(rows);
    		
    		// gerade oder ungerade Zeile setzen
			$(".tableTr").each(function () {    		
    		
    			rowCounter = rowCounter + 1;    			
    			
    			if (rowCounter % 2 > 0){
    				// odd-ungerade row
    				$(this).removeClass('tableTrOdd tableTrEven');
    				$(this).addClass('tableTrOdd');
    			}else{
    				// even-gerade row
    				$(this).removeClass('tableTrOdd tableTrEven');
    				$(this).addClass('tableTrEven');							
    			}    		
    		
    		});
    		
            showPager(1);  
            updateTableView(1);
			
		}
		
		
        function comparer(index) {
            return function(a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }
        
        function getCellValue(row, index){
        	return $(row).children('td').eq(index).text()
        }
        
        
        // Suchkriterien
        
        $(document).on('mouseover', '.searchOptionsHideSpan', function(){          	
          	
          	$(this).css('cursor','pointer');
                
        });
        
        $(document).on('click', '.searchOptionsHideSpan', function(){

    		if ($(this).closest('div').hasClass('searchOptionsVisible')){
				$(this).closest('div').removeClass('searchOptionsVisible');
				$(this).closest('div').addClass('searchOptionsInvisible');
				$(this).closest('div').html("<span class='searchOptionsHideSpan'>Suchfelder einblenden</span>");				
				$('.searchOptions').hide();						
						
    		}else{
				$(this).closest('div').removeClass('searchOptionsInvisible');
				$(this).closest('div').addClass('searchOptionsVisible');
				$(this).closest('div').html("<span class='searchOptionsHideSpan'>Suchfelder ausblenden</span>");
				$('.searchOptions').show();						    		
    		
    		}
                
        });
        
        $(document).on('click', '.searchOptionsButtonUndo', function(){
        	
        	// clear operators
            $('.searchOptionOperator').each(function () {
            	$(this).val('empty').change();
            });	
        	
        	// clear select-option value
        	$('.searchOptionValue').each(function () {
        		$(this).val('').change();
        	});
        	
        	// clear select-option dropdowns
        	$('.searchOptionValueSel').each(function () {
        		$(this).val('empty').change();
        	});	        		
        	
        	
        
        });
          
       		        
                                  		
		
	});
</script>


