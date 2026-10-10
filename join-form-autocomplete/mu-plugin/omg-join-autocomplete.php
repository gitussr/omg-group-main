<?php
/**
 * Plugin Name: OMG Join Form — Google Places Autocomplete
 * Description: Adds Google Places autocomplete to the Address field of Gravity Form id 2 (Join Our Team) and auto-populates Suburb, State, Postal Code and Country. Adds a search icon. Deployed as a must-use plugin.
 * Version:     1.0.0
 * Author:      OMG Group
 *
 * Must-use plugin: loads automatically, no activation needed. Remove this file
 * to fully revert. Replaces the "GF Google Address Autocomplete" plugin, which
 * is disabled as part of this deployment (it did not map the separate fields).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_enqueue_scripts', function () {

	// Register inline-only handles (no external file needed).
	wp_register_script( 'omg-join-aac', '', array(), '1.0.0', true );
	wp_enqueue_script( 'omg-join-aac' );

	wp_register_style( 'omg-join-aac', false, array(), '1.0.0' );
	wp_enqueue_style( 'omg-join-aac' );

	$css = <<<CSS
.omg-aac-field{position:relative}
.omg-aac-field .omg-aac-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);display:inline-flex;align-items:center;justify-content:center;color:#6b7280;cursor:text;z-index:2;line-height:0}
.omg-aac-field #input_2_17{padding-left:38px !important}
.pac-container{z-index:100000 !important}
CSS;
	wp_add_inline_style( 'omg-join-aac', $css );

	$key = 'AIzaSyAQPSgakhnhy0F8oSeb94We6qWr8wtoaJg';
	$js  = <<<JS
(function(){'use strict';
var CFG={apiKey:'{$key}',country:'au',fields:{address:'input_2_17',suburb:'input_2_18',state:'input_2_16',zip:'input_2_19',country:'input_2_20'}};
function byId(id){return document.getElementById(id);}
function setValue(id,v){var el=byId(id);if(!el||v==null)return;el.value=v;el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}));}
function setSelect(id,sn){var el=byId(id);if(!el||!sn)return;var w=String(sn).toUpperCase();for(var i=0;i<el.options.length;i++){if(el.options[i].value.toUpperCase()===w){el.selectedIndex=i;el.dispatchEvent(new Event('change',{bubbles:true}));if(window.jQuery&&window.jQuery(el).data('chosen')){window.jQuery(el).trigger('chosen:updated');}return;}}}
function pick(c,t,s){for(var i=0;i<c.length;i++){if(c[i].types.indexOf(t)!==-1){return s?c[i].short_name:c[i].long_name;}}return '';}
function fill(p){if(!p||!p.address_components)return;var c=p.address_components;var street=((pick(c,'street_number',false)+' '+pick(c,'route',false)).trim());var suburb=pick(c,'locality',false)||pick(c,'postal_town',false)||pick(c,'sublocality_level_1',false)||pick(c,'sublocality',false);var state=pick(c,'administrative_area_level_1',true);var zip=pick(c,'postal_code',false);var ctry=pick(c,'country',false);setValue(CFG.fields.address,street||p.name||'');setValue(CFG.fields.suburb,suburb);setSelect(CFG.fields.state,state);setValue(CFG.fields.zip,zip);setValue(CFG.fields.country,ctry);}
function addIcon(input){var box=input.closest('.ginput_container')||input.parentNode;if(!box||box.querySelector('.omg-aac-icon'))return;box.classList.add('omg-aac-field');var ic=document.createElement('span');ic.className='omg-aac-icon';ic.setAttribute('aria-hidden','true');ic.innerHTML='<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';box.insertBefore(ic,box.firstChild);ic.addEventListener('click',function(){input.focus();});}
function initField(){var input=byId(CFG.fields.address);if(!input||input.dataset.omgAac==='1')return;if(!(window.google&&google.maps&&google.maps.places))return;input.dataset.omgAac='1';addIcon(input);var a=new google.maps.places.Autocomplete(input,{componentRestrictions:{country:CFG.country},fields:['address_components','formatted_address','geometry','name'],types:['address']});a.addListener('place_changed',function(){fill(a.getPlace());});input.addEventListener('keydown',function(e){if(e.key==='Enter'||e.keyCode===13){var pac=document.querySelector('.pac-container');if(pac&&pac.offsetParent!==null)e.preventDefault();}});}
function ensure(){if(!byId(CFG.fields.address))return;if(window.google&&google.maps&&google.maps.places){initField();return;}if(window.__omgAacLoading){var t=setInterval(function(){if(window.google&&google.maps&&google.maps.places){clearInterval(t);initField();}},200);return;}window.__omgAacLoading=true;window.__omgAacInit=function(){initField();};var s=document.createElement('script');s.src='https://maps.googleapis.com/maps/api/js?key='+encodeURIComponent(CFG.apiKey)+'&libraries=places&loading=async&callback=__omgAacInit';s.async=true;s.defer=true;document.head.appendChild(s);}
if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',ensure);}else{ensure();}
if(window.jQuery){window.jQuery(document).on('gform_post_render',function(e,formId){if(formId==2)ensure();});}
})();
JS;
	wp_add_inline_script( 'omg-join-aac', $js );
} );
