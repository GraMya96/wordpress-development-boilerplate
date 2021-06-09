<?php add_action('admin_head', 'my_custom_admin_css');

function my_custom_admin_css() {
  echo '<style>
    .acf-postbox[id*="acf-group"] {
        margin: 40px 30px;
        box-shadow: 0px 0px 7px 2px rgba(0,0,0,.6);
    }
    .acf-postbox[id*="acf-group"] h2 {
        text-transform: uppercase;
        color: red !important;
    }
    .acf-postbox[id*="acf-group"] .acf-row-handle.order {
        background: #000;
        color: #fff;
    }

    .postbox.yoast.wpseo-metabox {
        margin: 40px 30px;
        box-shadow: 0px 0px 7px 2px rgba(0,0,0,.6);
    }
    .postbox.yoast.wpseo-metabox h2 {
        text-transform: uppercase;
        color: red !important;
    }
  </style>';
}