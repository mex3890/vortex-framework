<?php

namespace Core\Request;

class Paginator
{
    private string $request_url;
    private int $count_per_page;
    private int $current_page;
    private int $last_page;
    private string $pagination_links;
    private int $row_found;
    private const PREVIOUS_BUTTON_LABEL = 'Previous';
    private const NEXT_BUTTON_LABEL = 'Next';

    public function __construct(int $rows_found, int $count_per_page)
    {
        $this->count_per_page = $count_per_page;
        $this->row_found = $rows_found;
        $this->last_page = ceil($rows_found / $count_per_page);
        $this->current_page = $_GET['page'] ?? 1;
        $this->request_url = $this->getRequestPath();
    }

    private function getRequestPath()
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    private function createPaginationLinks(): void
    {
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

    }

    private function createHtmlLink($page_number, $request_url, $page_value, $is_link_active = ''): string
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

    private function getQueryStrings()
    {
        parse_str($_SERVER['QUERY_STRING'], $query_string);

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
        $this->createPreviousButton();
        $this->createPaginationLinks();
        $this->createNextButton();
        $this->finishPaginationLinks();

        return $this->pagination_links;
    }
}
