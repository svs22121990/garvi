                                              <tr class="tbl_tr_<?=$count; ?> textmult trRow " id="tbl_tr_<?=$count; ?>">
                                                          <td>
                                                             <select class="form-control cat_id select" id="cat_id<?=$count; ?>" name="cat_id[]" onchange="GetSubcategory(this.value,<?=$count; ?>)" data-live-search="true">
                                                                  <option value=''>Select Category</option>
                                                                  <?php foreach($category as $row) { ?>
                                                                  <option value="<?= $row->id ?>"><?= ucfirst($row->title); ?></option>    
                                                                  <?php } ?>
                                                                </select>
                                                          </td>
                                                          <td>
                                                            <select tabindex="3" class="form-control subcat_id select" id="subcat_id<?=$count; ?>" name="subcat_id[]" onchange="Getassetdata(this.value,<?=$count; ?>)" data-live-search="true">
                                                            <option value="">Select Subcategory</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <select tabindex="3" class="form-control asset_id select" id="asset_id<?=$count; ?>" name="asset_id[]" onchange="Getassetunit(this.value,<?=$count; ?>)" data-live-search="true">
                                                            <option value="">Select asset</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <input tabindex="4" type="text" name="quantity[]" id="quantity<?=$count; ?>" class="form-control quantity" placeholder="Quantity" value="1" maxlength="4" onkeypress="only_number(event)" autocomplete="off">
                                                          </td>
                                                          <td>
                                                           <input type="text" readonly="" placeholder="Unit" id="unit_val<?=$count; ?>" class="form-control unit_val">
                                                           <input type="hidden" placeholder="Unit" name="unit_id[]" id="unit_id<?=$count; ?>" class="form-control unit_id">
                                                          </td>
                                                          <td>
                                                            <button type="button" class="btn btn-danger m-b" style="width: 100%;font-size: 16px"  onclick="remove_tr($(this).closest('tr').index())" ><b>-</b></button> 
                                                          </td>
                                                        </tr>
