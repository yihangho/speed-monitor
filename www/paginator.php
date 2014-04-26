<?php
class Paginator {
	private $current_page, $total_pages;

	function __construct($_current, $_total) {
		$this->current_page = $_current;
		$this->total_pages = $_total;
	}

	private function get_classes_string($classes) {
		if (count($classes)) {
			return ' class = "' . join(" ", $classes) . '"';
		} else {
			return "";
		}
	}

	private function get_left_arrow() {
		$classes = [];

		if ($this->current_page == 1) {
			$classes[] = "disabled";
			$open_tag = "<span>";
			$close_tag = "</span>";
		} else {
			$target_page = $this->current_page - 1;
			$open_tag = "<a href=\"?page=$target_page\">";
			$close_tag = "</a>";
		}

		$classes_string = $this->get_classes_string($classes);

		$output = <<<"HEREDOC"
<li$classes_string>
	$open_tag
		&laquo;
	$close_tag
</li>
HEREDOC;

		return $output;
	}

	private function get_right_arrow() {
		$classes = [];

		if ($this->current_page == $this->total_pages) {
			$classes[] = "disabled";
			$open_tag = "<span>";
			$close_tag = "</span>";
		} else {
			$target_page = $this->current_page + 1;
			$open_tag = "<a href=\"?page=$target_page\">";
			$close_tag = "</a>";
		}

		$classes_string = $this->get_classes_string($classes);

		$output = <<<"HEREDOC"
<li$classes_string>
	$open_tag
		&raquo;
	$close_tag
</li>
HEREDOC;

		return $output;
	}

	private function get_item($index) {
		$classes = [];

		if ($this->current_page == $index) {
			$classes[] = "active";
			$open_tag = "<span>";
			$close_tag = "</span>";
		} elseif (is_string($index)) {
			$classes[] = "disabled";
			$open_tag = "<span>";
			$close_tag = "</span>";
		} else {
			$open_tag = "<a href=\"?page=$index\">";
			$close_tag = "</a>";
		}

		$classes_string = $this->get_classes_string($classes);

		$output = <<<"HEREDOC"
<li$classes_string>
	$open_tag
		$index
	$close_tag
</li>
HEREDOC;

		return $output;
	}

	function get_required_index() {
		$index = [];
		for ($i = 1; $i <= 3 && $i <= $this->total_pages; $i++) {
			$index[] = $i;
		}

		for ($i = max(4, $this->current_page - 2); $i <= $this->current_page + 2 && $i <= $this->total_pages; $i++) {
			$index[] = $i;
		}

		for ($i = max($this->current_page + 3, $this->total_pages - 2); $i <= $this->total_pages; $i++) {
			$index[] = $i;
		}

		return $index;
	}

	function get_output() {
		if ($this->total_pages <= 1) {
			return "";
		}

		$index = $this->get_required_index();

		$output = '<ul class="pagination">';

		$output .= $this->get_left_arrow();

		for ($i = 0; $i < count($index); $i++) {
			if ($i && $index[$i] != $index[$i-1] + 1) {
				$output .= $this->get_item("&#x22EF;");
			}
			$output .= $this->get_item($index[$i]);
		}

		$output .= $this->get_right_arrow();

		$output .= '</ul>';

		return $output;
	}
}
?>
