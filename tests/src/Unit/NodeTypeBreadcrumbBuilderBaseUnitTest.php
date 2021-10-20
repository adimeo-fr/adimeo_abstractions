<?php

namespace Drupal\Tests\adimeo_abstractions\Unit;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeTypeBreadcrumbBuilderBase;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Routing\RouteMatch;
use Drupal\node\Entity\Node;
use Drupal\Tests\UnitTestCase;

/**
 * Test description.
 *
 * @group adimeo_abstractions
 */
class NodeTypeBreadcrumbBuilderBaseUnitTest extends UnitTestCase {

  const METHOD_GET_NODE_TYPE = 'getNodeType';

  const METHOD_GET_LIST_ROUTE = 'getListRoute';

  const METHOD_GET_LIST_LABEL = 'getListLabel';

  const METHOD_GET_ROUTE_NAME = 'getRouteName';

  const METHOD_GET_PARAMETER = 'getParameter';

  const BUNDLE_METHOD = 'bundle';

  const ARTICLE_NODE_TYPE = 'article';

  const ARTICLE_LIST_ROUTE = 'adimeo.article_list';

  const ARTICLE_LIST_LABEL = 'Liste des articles';

  const COCKTAIL_NODE_TYPE = 'cocktail';

  const COCKTAIL_LIST_ROUTE = 'adimeo_cocktail_list';

  const COCKTAIL_LIST_LABEL = 'Liste des cocktails';

  /**
   * @var NodeTypeBreadcrumbBuilderBase
   */
  protected $articleBreadcrumbBuilder;

  /**
   * @var NodeTypeBreadcrumbBuilderBase
   */
  protected $cocktailBreadcrumbBuilder;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->articleBreadcrumbBuilder = $this->buildArticleMock();
    $this->cocktailBreadcrumbBuilder = $this->buildCocktailMock();
  }

  protected function buildArticleMock() {
    $breadcrumbBuilder = $this->getMockForAbstractClass(NodeTypeBreadcrumbBuilderBase::class);
    $breadcrumbBuilder->method(self::METHOD_GET_NODE_TYPE)
      ->willReturn(self::ARTICLE_NODE_TYPE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_ROUTE)
      ->willReturn(self::ARTICLE_LIST_ROUTE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_LABEL)
      ->willReturn(self::ARTICLE_LIST_LABEL);

    return $breadcrumbBuilder;
  }

  protected function buildCocktailMock() {
    $breadcrumbBuilder = $this->getMockForAbstractClass(NodeTypeBreadcrumbBuilderBase::class);
    $breadcrumbBuilder->method(self::METHOD_GET_NODE_TYPE)
      ->willReturn(self::COCKTAIL_NODE_TYPE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_ROUTE)
      ->willReturn(self::COCKTAIL_LIST_ROUTE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_LABEL)
      ->willReturn(self::COCKTAIL_LIST_LABEL);

    return $breadcrumbBuilder;
  }

  protected function createRouteMatchMock(string $routeName, ?string $bundle = NULL) {
    $routeMatch = $this->createMock(RouteMatch::class);
    $routeMatch->method(self::METHOD_GET_ROUTE_NAME)->willReturn($routeName);

    // should create a failure on any test against $routeMatch
    $getParameterValue = 'arbitrary string';
    if ($bundle) {
      $nodeMock = $this->createMock(Node::class);
      $nodeMock->method(self::BUNDLE_METHOD)->willReturn($bundle);
      $getParameterValue = $nodeMock;
    }

    $routeMatch->method(self::METHOD_GET_PARAMETER)
      ->willReturn($getParameterValue);

    return $routeMatch;
  }

  public function testArticleBreadcrumbIsAppliedOnArticleView() {
    $routeMatch = $this->createRouteMatchMock(NodeTypeBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE, self::ARTICLE_NODE_TYPE);
    $this->assertTrue($this->articleBreadcrumbBuilder->applies($routeMatch), "Article Breadcrumb is not applied on Article view route");
  }

  public function testArticleBreadcrumbIsNotAppliedOnCocktailView() {
    $routeMatch = $this->createRouteMatchMock(NodeTypeBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE, self::COCKTAIL_NODE_TYPE);
    $this->assertFalse($this->articleBreadcrumbBuilder->applies($routeMatch), 'Article Breadcrumb is applied on Cocktail view route');
  }

  public function testArticleBreadcrumbTriggersErrorIfRouteIsNodeViewAndNodeParameterIsNotNode() {
    $routeMatch = $this->createRouteMatchMock(NodeTypeBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE);
    $this->expectError();
    $this->articleBreadcrumbBuilder->applies($routeMatch);
  }

  public function testArticleBreadcrumbIsAppliedOnArticleRevision() {
    $routeMatch = $this->createRouteMatchMock(NodeTypeBreadcrumbBuilderBase::NODE_REVISION_ROUTE, self::ARTICLE_NODE_TYPE);
    $this->assertTrue($this->articleBreadcrumbBuilder->applies($routeMatch), "Article Breadcrumb is not applied on Article revision route");
  }

  public function testArticleBreadcrumbIsAppliedOnArticleList() {
    $routeMatch = $this->createRouteMatchMock(self::ARTICLE_LIST_ROUTE);
    $this->assertTrue($this->articleBreadcrumbBuilder->applies($routeMatch), "Article Breadcrumb is not applied on Article list route");
  }

  public function testCocktailBreadcrumbIsNotAppliedOnArticleList() {
    $routeMatch = $this->createRouteMatchMock(self::ARTICLE_LIST_ROUTE);
    $this->assertFalse($this->cocktailBreadcrumbBuilder->applies($routeMatch), "Cocktail Breadcrumb is applied on Article list route");
  }

  public function testBuildArticleNodeViewBreadcrumbIsABreadcrumb() {
    $routeMatch = $this->createRouteMatchMock(NodeTypeBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE, self::ARTICLE_NODE_TYPE);
    $breadcrumb = $this->articleBreadcrumbBuilder->build($routeMatch);
    $this->assertTrue($breadcrumb instanceof Breadcrumb, "Return of Article Breadcrumb build is not a breadcrumb");
  }

  public function testBuildArticleNodeViewBreadcrumbContainsThreeElements() {
    $routeMatch = $this->createRouteMatchMock(NodeTypeBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE, self::ARTICLE_NODE_TYPE);
    $breadcrumb = $this->articleBreadcrumbBuilder->build($routeMatch);
    $this->assertTrue(sizeof($breadcrumb->getLinks()) === 3, "Article node view Breadcrumb does not contain 3 elements");
  }
}
