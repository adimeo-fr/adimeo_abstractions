<?php

namespace Drupal\Tests\adimeo_abstractions\Unit\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\HomeLinkTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase;
use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Routing\RouteMatch;
use Drupal\node\Entity\Node;
use Drupal\Tests\adimeo_abstractions\Unit\BreadcrumbBuilder\Fixtures\CreateRouteMatchMockTrait;
use Drupal\Tests\UnitTestCase;

/**
 * Test description.
 *
 * @group adimeo_abstractions
 */
class NodeBundleRouteListBreadcrumbBuilderBaseUnitTest extends UnitTestCase {

  const METHOD_GET_NODE_BUNDLE = 'getNodeBundle';

  const METHOD_GET_LIST_ROUTE = 'getListRoute';

  const METHOD_GET_LIST_LABEL = 'getListLabel';

  const METHOD_GET_ROUTE_NAME = 'getRouteName';

  const METHOD_GET_PARAMETER = 'getParameter';

  const BUNDLE_METHOD = 'bundle';

  const METHOD_GET_TITLE = 'getTitle';

  const ARTICLE_NODE_BUNDLE = 'article';

  const ARTICLE_LIST_ROUTE = 'adimeo.article_list';

  const ARTICLE_LIST_LABEL = 'Liste des articles';

  const COCKTAIL_NODE_BUNDLE = 'cocktail';

  const COCKTAIL_LIST_ROUTE = 'adimeo_cocktail_list';

  const COCKTAIL_LIST_LABEL = 'Liste des cocktails';

  const ARTICLE_NODE_TITLE = 'Article test';

  const METHOD_BUNDLE = 'bundle';

  const METHOD_GET_CACHE_TAGS = 'getCacheTags';

  const URL_CACHE_CONTEXT = 'url';

  /**
   * @var NodeBundleRouteListBreadcrumbBuilderBase
   */
  protected $articleBreadcrumbBuilder;

  /**
   * @var NodeBundleRouteListBreadcrumbBuilderBase
   */
  protected $cocktailBreadcrumbBuilder;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->articleBreadcrumbBuilder = $this->buildArticleBreadcrumbBuilderMock();
    $this->cocktailBreadcrumbBuilder = $this->buildCocktailBreadcrumbBuilderMock();
  }

  protected function buildArticleBreadcrumbBuilderMock() {
    $breadcrumbBuilder = $this->getMockForAbstractClass(NodeBundleRouteListBreadcrumbBuilderBase::class);
    $breadcrumbBuilder->method(self::METHOD_GET_NODE_BUNDLE)
      ->willReturn(self::ARTICLE_NODE_BUNDLE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_ROUTE)
      ->willReturn(self::ARTICLE_LIST_ROUTE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_LABEL)
      ->willReturn(self::ARTICLE_LIST_LABEL);

    return $breadcrumbBuilder;
  }

  protected function buildCocktailBreadcrumbBuilderMock() {
    $breadcrumbBuilder = $this->getMockForAbstractClass(NodeBundleRouteListBreadcrumbBuilderBase::class);
    $breadcrumbBuilder->method(self::METHOD_GET_NODE_BUNDLE)
      ->willReturn(self::COCKTAIL_NODE_BUNDLE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_ROUTE)
      ->willReturn(self::COCKTAIL_LIST_ROUTE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_LABEL)
      ->willReturn(self::COCKTAIL_LIST_LABEL);

    return $breadcrumbBuilder;
  }

  protected function getCorrectArticleViewRouteMatch() {
    return $this->createRouteMatchMock(RoutesDefinitions::NODE_CANONICAL, self::ARTICLE_NODE_BUNDLE);
  }

  protected function getCorrectArticleRevisionRouteMatch() {
    return $this->createRouteMatchMock(RoutesDefinitions::NODE_REVISION, self::ARTICLE_NODE_BUNDLE);
  }

  protected function getCorrectCocktailViewRouteMatch() {
    return $this->createRouteMatchMock(RoutesDefinitions::NODE_CANONICAL, self::COCKTAIL_NODE_BUNDLE);;
  }

  protected function getIncorrectViewRouteMatch() {
    return $this->createRouteMatchMock(RoutesDefinitions::NODE_CANONICAL);
  }

  protected function getArticleListRouteMatch() {
    return $this->createRouteMatchMock(self::ARTICLE_LIST_ROUTE);
  }

  protected function getBuiltArticleViewBreadcrumb() {
    $routeMatch = $this->getCorrectArticleViewRouteMatch();
    return $this->articleBreadcrumbBuilder->build($routeMatch);
  }

  protected function getBuiltArticleRevisionBreadcrumb() {
    $routeMatch = $this->getCorrectArticleRevisionRouteMatch();
    return $this->articleBreadcrumbBuilder->build($routeMatch);
  }

  protected function getBuiltArticleListBreadcrumb() {
    $routeMatch = $this->getArticleListRouteMatch();
    return $this->articleBreadcrumbBuilder->build($routeMatch);
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsAppliedOnArticleView() {
    $routeMatch = $this->getCorrectArticleViewRouteMatch();
    $this->assertTrue(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      "Article breadcrumb is not applied on article view route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsNotAppliedOnCocktailView() {
    $routeMatch = $this->getCorrectCocktailViewRouteMatch();
    $this->assertFalse(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      'Article breadcrumb is applied on cocktail view route'
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbTriggersErrorIfRouteIsNodeViewAndNodeParameterIsNotNode() {
    $routeMatch = $this->getIncorrectViewRouteMatch();
    $this->expectError();
    $this->articleBreadcrumbBuilder->applies($routeMatch);
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsAppliedOnArticleRevision() {
    $routeMatch = $this->getCorrectArticleRevisionRouteMatch();
    $this->assertTrue(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      "Article breadcrumb is not applied on article revision route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsAppliedOnArticleList() {
    $routeMatch = $this->getArticleListRouteMatch();
    $this->assertTrue(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      "Article breadcrumb is not applied on article list route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testCocktailBreadcrumbIsNotAppliedOnArticleList() {
    $routeMatch = $this->getArticleListRouteMatch();
    $this->assertFalse(
      $this->cocktailBreadcrumbBuilder->applies($routeMatch),
      "Cocktail breadcrumb is applied on article list route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbIsABreadcrumb() {
    $this->assertTrue(
      $this->getBuiltArticleViewBreadcrumb() instanceof Breadcrumb,
      "Return of article breadcrumb build is not a breadcrumb"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsThreeElements() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $this->assertTrue(
      sizeof($breadcrumb->getLinks()) === 3,
      "Article node view breadcrumb does not contain 3 elements"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsLinkToHomeAsFirstLink() {
    $this->testBuiltBreadcrumbContainsLinkToHomeAsFirstLink(
      $this->getBuiltArticleListBreadcrumb(),
      "Article bode view breadcrumb does not contain link to home as first link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleNodeViewBreadcrumbContainsLinkLabelledAfterHomeAsFirstLink() {
    $this->testBuiltBreadcrumbContainsLinkLabelledAfterHomeAsFirstLink(
      $this->getBuiltArticleViewBreadcrumb(),
      "Article node view breadcrumb does not contain link labelled after home as first link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleNodeViewBreadcrumbContainsLinkToListAsSecondLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $secondLink = $breadcrumb->getLinks()[1];
    $this->assertTrue(
      $secondLink->getUrl()->getRouteName() === self::ARTICLE_LIST_ROUTE,
      "Article node view breadcrumb does not contain link to node list as second link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleNodeViewBreadcrumbContainsLinkLabelledAfterListAsSecondLink() {
    $this->testBuiltBreadcrumbContainsLinkLabelledAfterListAsSecondLink(
      $this->getBuiltArticleViewBreadcrumb(),
      "Article node view breadcrumb does not contain link labelled after node list as second link",
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleNodeViewBreadcrumbContainsEmptyLinkAsThirdLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $thirdLink = $breadcrumb->getLinks()[2];
    $this->assertTrue(
      $thirdLink->getUrl()
        ->getRouteName() === RoutesDefinitions::NONE,
      "Article node view breadcrumb does not contain empty link as third link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleNodeViewBreadcrumbContainsLinkLabelledAfterNodeTitleAsThirdLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $thirdLink = $breadcrumb->getLinks()[2];
    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup $linkText */
    $linkText = $thirdLink->getText();
    $this->assertTrue(
      $linkText->getUntranslatedString() === self::ARTICLE_NODE_TITLE,
      "Article node view breadcrumb does not contain link labelled after node title as third link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuitdArticleNodeRevisionBreadcrumbContainsSameLinksAsNodeViewOne() {
    $nodeViewBreadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $revisionBreadcrumb = $this->getBuiltArticleRevisionBreadcrumb();
    $this->assertTrue(
      serialize($nodeViewBreadcrumb->getLinks()) === serialize($revisionBreadcrumb->getLinks()),
      "Article revision Breadcrumb is not equal to article node view breadcrumb"
    );
  }

  public function testBuiltArticleListBreadcrumbContainsTwoLinks() {
    $breadcrumb = $this->getBuiltArticleListBreadcrumb();
    $this->assertTrue(
      sizeof($breadcrumb->getLinks()) === 2,
      "Article list breadcrumb does not contain 2 elements"
    );
  }

  public function testBuildArticleListBreadcrumbContainsLinkToHomeAsFirstLink() {
    $this->testBuiltBreadcrumbContainsLinkToHomeAsFirstLink(
      $this->getBuiltArticleListBreadcrumb(),
      "Article list breadcrumb does not contain link to home as first link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleListBreadcrumbContainsLinkLabelledAfterHomeAsFirstLink() {
    $this->testBuiltBreadcrumbContainsLinkLabelledAfterHomeAsFirstLink(
      $this->getBuiltArticleListBreadcrumb(),
      "Article list breadcrumb does not contain link labelled after home as first link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleListBreadcrumbContainsEmptyLinkAsSecondLink() {
    $breadcrumb = $this->getBuiltArticleListBreadcrumb();
    $secondLink = $breadcrumb->getLinks()[1];
    $this->assertTrue(
      $secondLink->getUrl()
        ->getRouteName() === RoutesDefinitions::NONE,
      "Article list breadcrumb does not contain an empty link as second link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleListBreadcrumbContainsLinkLabelledAfterListAsSecondLink() {
    $this->testBuiltBreadcrumbContainsLinkLabelledAfterListAsSecondLink(
      $this->getBuiltArticleListBreadcrumb(),
      "Article list breadcrumb does not contain link labelled after node list as second link",
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleRouteListBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuiltArticleNodeViewBreadcrumbContainsUrlCacheContext() {
    $this->testBuiltBreadcrumbContainsUrlCacheContext(
      $this->getBuiltArticleViewBreadcrumb(),
      "Article node view breadcrumb does not contain url cache context",
    );
  }

  protected function testBuiltBreadcrumbContainsLinkToHomeAsFirstLink(Breadcrumb $breadcrumb, string $message) {
    $firstLink = $breadcrumb->getLinks()[0];
    $this->assertTrue(
      $firstLink->getUrl()
        ->getRouteName() === RoutesDefinitions::FRONT,
      $message
    );
  }

  protected function testBuiltBreadcrumbContainsLinkLabelledAfterHomeAsFirstLink(Breadcrumb $breadcrumb, string $message) {
    $firstLink = $breadcrumb->getLinks()[0];
    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup $linkText */
    $linkText = $firstLink->getText();
    $this->assertTrue(
      $linkText->getUntranslatedString() === HomeLinkTrait::getHomeLabel(),
      $message
    );
  }

  protected function testBuiltBreadcrumbContainsLinkLabelledAfterListAsSecondLink(Breadcrumb $breadcrumb, string $message) {
    $secondLink = $breadcrumb->getLinks()[1];
    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup $linkText */
    $linkText = $secondLink->getText();
    $this->assertTrue(
      $linkText->getUntranslatedString() === self::ARTICLE_LIST_LABEL,
      $message
    );
  }

  protected function testBuiltBreadcrumbContainsUrlCacheContext(Breadcrumb $breadcrumb, string $message) {
    $this->assertTrue(
      in_array(self::URL_CACHE_CONTEXT, $breadcrumb->getCacheContexts()),
      $message
    );
  }

  protected function createRouteMatchMock(string $routeName, ?string $bundle = NULL) {
    $routeMatch = $this->createMock(RouteMatch::class);
    $routeMatch->method(self::METHOD_GET_ROUTE_NAME)->willReturn($routeName);

    // should create a failure on any test against $routeMatch
    $getParameterValue = 'arbitrary string';
    if ($bundle) {
      $nodeMock = $this->createMock(Node::class);
      $nodeMock->method(self::METHOD_BUNDLE)->willReturn($bundle);
      $nodeMock->method(self::METHOD_GET_TITLE)
        ->willReturn(self::ARTICLE_NODE_TITLE);
      $getParameterValue = $nodeMock;
    }

    $routeMatch->method(self::METHOD_GET_PARAMETER)
      ->willReturn($getParameterValue);

    return $routeMatch;
  }

}
