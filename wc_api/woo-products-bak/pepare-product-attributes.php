<?php

function ps_woo_format_attributes($ProductAttributes, $ForVariation){

  $FormattedAttributes = array();
  foreach($ProductAttributes as $Name => $Values) {

    $ValuesList = explode('|', $Values);

    $FormattedAttributes['pa_' . $Name] = array(
      'term_names' => $ValuesList,
      'for_variation' => $ForVariation
    );
  }

  $data = array();
  $position = 0;

  foreach($FormattedAttributes as $taxonomy => $values){

    $attribute = new WC_Product_Attribute();

    $term_ids = array();			
    foreach($values['term_names'] as $term_name){

      if(term_exists($term_name, $taxonomy))
        $term_ids[] = get_term_by('name', $term_name, $taxonomy)->term_id;
      else
        $term_ids[] = wp_insert_term($term_name, $taxonomy)->term_id;
    }

    $taxonomy_id = wc_attribute_taxonomy_id_by_name($taxonomy);
    $attribute->set_id($taxonomy_id);
    $attribute->set_name($taxonomy);
    $attribute->set_options($term_ids);
    $attribute->set_position($position);
    $attribute->set_visible(true);
    $attribute->set_variation($values['for_variation']);

    $data[$taxonomy] = $term_ids;

    $position++; // Increase position
  }
  
	return $data;
}