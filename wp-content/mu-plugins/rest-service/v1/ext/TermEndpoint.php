<?php
namespace MU_Plugins\Rest_Service\V1\Ext;

class TermEndpoint implements BaseRestfulEndpoint {
    public function registerEndpoints(string $namespace) {
        \register_rest_route($namespace,'/term/(?P<term>[A-Za-z0-9\-\_\"\']+)',[
            "methods" => \WP_REST_Server::READABLE,
            "callback" => function(\WP_REST_Request $Request): ?\WP_Term {
                return $this->getTerm($Request);
            }
        ]);
    }

    protected function getTerm(\WP_REST_Request $Request): ?\WP_Term {
        global $post;
        $term = $Request->get_param('term');
        $taxonomy = $Request->get_param('taxonomy');
        $Term = null;
        if($taxonomy == "post_tag" || $taxonomy == "tag") {
            $Term = get_term_by((is_numeric($term) ? "id" : "slug"),$term,"post_tag",OBJECT);
        } else {
            $Term = get_term_by((is_numeric($term) ? "id" : "slug"),$term,"category",OBJECT);
        }
        if(!$Term) {
            $Term = null;
        }

        return $Term;
    }
}
