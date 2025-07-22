# How to Build a Custom REST API Endpoint

OpenDXP offers a bundle called [Datahub](https://github.com/open-dxp/data-hub), offering a highly configurable GraphQL interface on most OpenDXP entities.

However a common use case for applications build with OpenDXP is integrating with external systems,
which requires custom response from API endpoints.

One way to achieve this requirement is to build custom controller action that exposes just the right data
in the desired format.

### Example

```php
<?php

namespace App\Controller;

use OpenDxp\Model\DataObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \OpenDxp\Controller\FrontendController;

class CustomRestController extends FrontendController
{
    /**
     * @Route("/custom-opendxp-webservice/rest/get-products")
     */
    public function defaultAction(Request $request): JsonResponse
    {
        // do some authorization here ...

        $blogs = new DataObject\BlogArticle\Listing();

        foreach ($blogs as $key => $blog) {
            $data[] = array(
                "title" => $blog->getTitle(),
                "description" => $blog->getText(),
                "tags" => $blog->getTags());
        }

        return $this->json(["success" => true, "data" => $data]);
    }
}

```

Sometimes it is necessary to serialize complete element for API  response.
This can be achieved by [overriding a model](../20_Extending_OpenDxp/03_Overriding_Models.md)
which implements the `\JsonSerializable` interface and implementing `jsonSerialize` method to return the data you require to be serialized.

 ```php
 <?php

 namespace App\Model\DataObject;

 class BlogArticle extends \OpenDxp\Model\DataObject\BlogArticle implements \JsonSerializable
 {
     public function jsonSerialize(): array
     {
         $vars = get_object_vars($this);

         return $vars;
     }
 }
 ```
