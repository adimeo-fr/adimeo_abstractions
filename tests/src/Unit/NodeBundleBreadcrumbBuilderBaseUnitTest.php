<?php

namespace Drupal\Tests\adimeo_abstractions\Unit;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Routing\RouteMatch;
use Drupal\node\Entity\Node;
use Drupal\Tests\UnitTestCase;

/**
 * Test description.
 *
 * @group adimeo_abstractions
 */
class NodeBundleBreadcrumbBuilderBaseUnitTest extends UnitTestCase {

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

  /**
   * @var NodeBundleBreadcrumbBuilderBase
   */
  protected $articleBreadcrumbBuilder;

  /**
   * @var NodeBundleBreadcrumbBuilderBase
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
    $breadcrumbBuilder = $this->getMockForAbstractClass(NodeBundleBreadcrumbBuilderBase::class);
    $breadcrumbBuilder->method(self::METHOD_GET_NODE_BUNDLE)
      ->willReturn(self::ARTICLE_NODE_BUNDLE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_ROUTE)
      ->willReturn(self::ARTICLE_LIST_ROUTE);
    $breadcrumbBuilder->method(self::METHOD_GET_LIST_LABEL)
      ->willReturn(self::ARTICLE_LIST_LABEL);

    return $breadcrumbBuilder;
  }

  protected function buildCocktailMock() {
    $breadcrumbBuilder = $this->getMockForAbstractClass(NodeBundleBreadcrumbBuilderBase::class);
    $breadcrumbBuilder->method(self::METHOD_GET_NODE_BUNDLE)
      ->willReturn(self::COCKTAIL_NODE_BUNDLE);
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
      $nodeMock->method(self::METHOD_GET_TITLE)->willReturn(self::ARTICLE_NODE_TITLE);
      $getParameterValue = $nodeMock;
    }

    $routeMatch->method(self::METHOD_GET_PARAMETER)
      ->willReturn($getParameterValue);

    return $routeMatch;
  }

  protected function getCorrectArticleViewRouteMatch() {
    return $this->createRouteMatchMock(NodeBundleBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE, self::ARTICLE_NODE_BUNDLE);
  }

  protected function getCorrectArticleRevisionRouteMatch() {
    return $this->createRouteMatchMock(NodeBundleBreadcrumbBuilderBase::NODE_REVISION_ROUTE, self::ARTICLE_NODE_BUNDLE);
  }

  protected function getCorrectCocktailViewRouteMatch() {
    return $this->createRouteMatchMock(NodeBundleBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE, self::COCKTAIL_NODE_BUNDLE);;
  }

  protected function getIncorrectViewRouteMatch() {
    return $this->createRouteMatchMock(NodeBundleBreadcrumbBuilderBase::NODE_CANONICAL_ROUTE);
  }

  protected function getArticleListRouteMatch() {
    return $this->createRouteMatchMock(self::ARTICLE_LIST_ROUTE);
  }

  protected function getBuiltArticleViewBreadcrumb() {
    $routeMatch = $this->getCorrectArticleViewRouteMatch();
    return $this->articleBreadcrumbBuilder->build($routeMatch);
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsAppliedOnArticleView() {
    $routeMatch = $this->getCorrectArticleViewRouteMatch();
    $this->assertTrue(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      "Article Breadcrumb is not applied on Article view route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsNotAppliedOnCocktailView() {
    $routeMatch = $this->getCorrectCocktailViewRouteMatch();
    $this->assertFalse(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      'Article Breadcrumb is applied on Cocktail view route'
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::applies
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
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsAppliedOnArticleRevision() {
    $routeMatch = $this->getCorrectArticleRevisionRouteMatch();
    $this->assertTrue(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      "Article Breadcrumb is not applied on Article revision route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testArticleBreadcrumbIsAppliedOnArticleList() {
    $routeMatch = $this->getArticleListRouteMatch();
    $this->assertTrue(
      $this->articleBreadcrumbBuilder->applies($routeMatch),
      "Article Breadcrumb is not applied on Article list route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::applies
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testCocktailBreadcrumbIsNotAppliedOnArticleList() {
    $routeMatch = $this->getArticleListRouteMatch();
    $this->assertFalse(
      $this->cocktailBreadcrumbBuilder->applies($routeMatch),
      "Cocktail Breadcrumb is applied on Article list route"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbIsABreadcrumb() {
    $this->assertTrue(
      $this->getBuiltArticleViewBreadcrumb() instanceof Breadcrumb,
      "Return of Article Breadcrumb build is not a breadcrumb"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsThreeElements() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $this->assertTrue(
      sizeof($breadcrumb->getLinks()) === 3,
      "Article node view Breadcrumb does not contain 3 elements"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsLinkToHomeAsFirstLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $firstLink = $breadcrumb->getLinks()[0];
    $this->assertTrue($firstLink->getUrl()
        ->getRouteName() === NodeBundleBreadcrumbBuilderBase::FRONT_ROUTE, "Article node view Breadcrumb does not contain link to home as first link");
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsLinkLabelledAfterHomeAsFirstLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $firstLink = $breadcrumb->getLinks()[0];
    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup $linkText */
    $linkText = $firstLink->getText();
    $this->assertTrue(
      $linkText->getUntranslatedString() === NodeBundleBreadcrumbBuilderBase::HOME_LABEL,
      "Article node view Breadcrumb does not contain link labelled after home as first link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsLinkToListAsSecondLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $secondLink = $breadcrumb->getLinks()[1];
    $this->assertTrue(
      $secondLink->getUrl()->getRouteName() === self::ARTICLE_LIST_ROUTE,
      "Article node view Breadcrumb does not contain link to node list as second link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsLinkLabelledAfterListAsSecondLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $secondLink = $breadcrumb->getLinks()[1];
    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup $linkText */
    $linkText = $secondLink->getText();
    $this->assertTrue(
      $linkText->getUntranslatedString() === self::ARTICLE_LIST_LABEL,
      "Article node view Breadcrumb does not contain link labelled after node list as second link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsEmptyLinkAsThirdLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $thirdLink = $breadcrumb->getLinks()[2];
    $this->assertTrue(
      $thirdLink->getUrl()->getRouteName() === NodeBundleBreadcrumbBuilderBase::NONE_ROUTE,
      "Article node view Breadcrumb does not contain empty link as third link"
    );
  }

  /**
   * @covers \Drupal\adimeo_abstractions\BreadcrumbBuilder\NodeBundleBreadcrumbBuilderBase::build
   * @author adimeo
   * @group adimeoAbstractions
   * @group breadcrumbBuilder
   */
  public function testBuildArticleNodeViewBreadcrumbContainsLinkLabelledAfterNodeTitleAsThirdLink() {
    $breadcrumb = $this->getBuiltArticleViewBreadcrumb();
    $thirdLink = $breadcrumb->getLinks()[2];
    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup $linkText */
    $linkText = $thirdLink->getText();
    $this->assertTrue(
      $linkText->getUntranslatedString() === self::ARTICLE_NODE_TITLE,
      "Article node view Breadcrumb does not contain link labelled after node title as third link"
    );
  }
}
