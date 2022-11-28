<?php

namespace Core\Request;

class Paginator
{
    private string $request_url;
    private int $count_per_page;
    private int $current_page;
    private int $last_page;
    private string $pagination_links = '';
    private bool $with_previous_button;
    private bool $with_next_button;
    private int $max_pages_before_break;
    private const PREVIOUS_BUTTON_LABEL = 'Previous';
    private const NEXT_BUTTON_LABEL = 'Next';

    public function __construct(
        int  $rows_found,
        int  $count_per_page,
        bool $with_previous_button,
        bool $with_next_button,
        int  $max_pages_before_break
    )
    {
        $this->count_per_page = $count_per_page;
        $this->last_page = ceil($rows_found / $count_per_page);
        $this->current_page = $_GET['page'] ?? 1;
        $this->request_url = $this->getRequestPath();
        $this->with_previous_button = $with_previous_button;
        $this->with_next_button = $with_next_button;
        $this->max_pages_before_break = $max_pages_before_break;
    }

    private function getRequestPath()
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    private function createPaginationLinks(): void
    {
        if ($this->last_page <= $this->max_pages_before_break) {
            for ($page = 1; $page <= $this->last_page; $page++) {
                $is_link_active = '';

                if ((int)$this->current_page === $page) {
                    $is_link_active = 'active';
                }

                $query_strings = $this->getQueryStrings();
                unset($query_strings['page']);
                $request_url = $this->request_url . "?" . http_build_query($query_strings);

                $this->pagination_links .= $this->createHtmlLink($page, $request_url, $page, $is_link_active);
            }
        } else {
            if ($this->current_page === 1 || $this->current_page === $this->last_page) {
                for ($page = 1; $page <= 3; $page++) {
                    $is_link_active = '';

                    if ((int)$this->current_page === $page) {
                        $is_link_active = 'active';
                    }

                    $query_strings = $this->getQueryStrings();
                    unset($query_strings['page']);
                    $request_url = $this->request_url . "?" . http_build_query($query_strings);

                    $this->pagination_links .= $this->createHtmlLink($page, $request_url, $page, $is_link_active);
                }

                $this->pagination_links .= $this->createHtmlEllipsesLink();

                for ($page = $this->last_page - 2; $page <= $this->last_page; $page++) {
                    $is_link_active = '';

                    if ((int)$this->current_page === $page) {
                        $is_link_active = 'active';
                    }

                    $query_strings = $this->getQueryStrings();
                    unset($query_strings['page']);
                    $request_url = $this->request_url . "?" . http_build_query($query_strings);

                    $this->pagination_links .= $this->createHtmlLink($page, $request_url, $page, $is_link_active);
                }
            } else {
                if ($this->current_page - 2 > 0) {
                    $query_strings = $this->getQueryStrings();
                    unset($query_strings['page']);
                    $request_url = $this->request_url . "?" . http_build_query($query_strings);

                    $this->pagination_links .= $this->createHtmlLink(1, $request_url, 1);

                    if ($this->current_page - 2 > 1) {
                        $this->pagination_links .= $this->createHtmlEllipsesLink();
                    }
                }

                for ($page = $this->current_page - 1; $page <= $this->current_page + 1; $page++) {
                    $is_link_active = '';

                    if ((int)$this->current_page === $page) {
                        $is_link_active = 'active';
                    }

                    $query_strings = $this->getQueryStrings();
                    unset($query_strings['page']);
                    $request_url = $this->request_url . "?" . http_build_query($query_strings);

                    $this->pagination_links .= $this->createHtmlLink($page, $request_url, $page, $is_link_active);
                }

                if ($this->current_page + 2 < $this->last_page) {
                    $this->pagination_links .= $this->createHtmlEllipsesLink();
                    $this->pagination_links .= $this->createHtmlLink($this->last_page, "$request_url&page=$this->last_page", $this->last_page);
                }

                if ($this->current_page + 2 === $this->last_page) {
                    $this->pagination_links .= $this->createHtmlLink($this->last_page, "$request_url&page=$this->last_page", $this->last_page);
                }
            }
        }
    }

    private function createHtmlLink(int $page_number, string $request_url, int|string $page_value, ?string $is_link_active = ''): string
    {
        $button_class = '';

        if ($page_value === self::PREVIOUS_BUTTON_LABEL || $page_value === self::NEXT_BUTTON_LABEL) {
            $button_class = 'lateral_button_link';
        }

        return "<li class='page-item $button_class $is_link_active'>
                    <a class='page-link'  href='$request_url&page=$page_number' class = 'page-link'>$page_value
                    </a>
               </li>
               ";
    }

    private function createHtmlEllipsesLink(): string
    {
        return "<li class='page-item'><a class='page-link'>...</a></li>";
    }

    private function getQueryStrings()
    {
        if (!isset($_SERVER['QUERY_STRING'])) {
            parse_str('&page=1', $query_string);
        } else {
            parse_str($_SERVER['QUERY_STRING'], $query_string);
        }

        return $query_string;
    }

    private function createPreviousButton(): void
    {
        if ($this->current_page > 1) {
            $previous_page = $this->current_page - 1;
            $query_strings = $this->getQueryStrings();
            unset($query_strings['page']);
            $request_url = $this->request_url . "?" . http_build_query($query_strings);
            $this->pagination_links .= $this->createHtmlLink($previous_page, $request_url, self::PREVIOUS_BUTTON_LABEL);
        }
    }

    private function createNextButton(): void
    {
        if ($this->last_page != 1) {
            if ($this->current_page != $this->last_page) {
                $next_page = $this->current_page + 1;
                $query_strings = $this->getQueryStrings();
                unset($query_strings['page']);
                $request_url = $this->request_url . "?" . http_build_query($query_strings);
                $this->pagination_links .= $this->createHtmlLink($next_page, $request_url, self::NEXT_BUTTON_LABEL);
            }
        }
    }

    public function getOffsetAndLimit(): array
    {
        if (isset($this->current_page) && isset($this->count_per_page)) {
            return [
                'min' => ($this->current_page - 1) * $this->count_per_page,
                'max' => $this->count_per_page
            ];
        } else {
            return [];
        }
    }

    private function finishPaginationLinks(): void
    {
        $this->pagination_links = "<nav class='pagination-nav'><ul class='pagination-links-list'>$this->pagination_links</ul></nav>";
    }

    public function mountLinks(): string
    {
        if ($this->with_previous_button) {
            $this->createPreviousButton();
        }

        $this->createPaginationLinks();

        if ($this->with_next_button) {
            $this->createNextButton();
        }

        $this->finishPaginationLinks();

        return $this->pagination_links;
    }
}
