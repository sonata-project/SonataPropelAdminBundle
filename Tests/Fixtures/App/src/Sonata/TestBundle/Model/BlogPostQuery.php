<?php

namespace Sonata\TestBundle\Model;

use Sonata\TestBundle\Model\om\BaseBlogPostQuery;

class BlogPostQuery extends BaseBlogPostQuery
{
    public function filterByIsPublished()
    {
        return $this->filterByPublished(true);
    }
}
