<?php

class Model {
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

    public function readAll() {
		$sql = 'SELECT * FROM `director`';
		$stm = $this->pdo->prepare($sql);
		$stm->execute();
		$results = $stm->fetchAll(PDO::FETCH_ASSOC);
		return array_map(function ($item) {
		    return new Director($item);
        }, $results);
	}

    public function readMovies($directorId) {
        $sql = 'SELECT * FROM `films` WHERE `director_id` = :directorId';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":directorId", $directorId);
        $stm->execute();
        return new Movie($stm->fetch(PDO::FETCH_ASSOC));
    }

	public function getById($id) {
		$sql = 'SELECT * FROM `films` WHERE `id`=:id';
		$stm = $this->pdo->prepare($sql);
		$stm->bindValue(":id", $id);
		$stm->execute();
		return new Movie ($stm->fetch(PDO::FETCH_ASSOC));
	}

	public function update(Movie $movie) {
        $sql = 'UPDATE `films` SET `title`=:title, `altTitle`=:altTitle, `director`=:director, `country`=:country, `year`=:year WHERE `id`=:id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(":id", $movie->getId());
        $stm->bindValue(":title", $movie->getTitle());
        $stm->bindValue(":alt_title", $movie->getAltTitle());
        $stm->bindValue(":director", $movie->getDirectorId());
        $stm->bindValue(":country", $movie->getCountry());
        $stm->bindValue(":year", $movie->getYear());
        return $stm->execute();
    }

    public function create(Movie $movie) {
		$sql = 'INSERT INTO `films` (`title`, `altTitle`, `director`, `country`, `year`) VALUES (:title, :altTitle, :director, :country, :year)';
		$stm = $this->pdo->prepare($sql);
        $stm->bindValue(":title", $movie->getTitle());
		$stm->bindValue(":alt_title",$movie->getAltTitle());
		$stm->bindValue(":director",$movie->getDirectorId());
		$stm->bindValue(":country",$movie->getCountry());
		$stm->bindValue(":year",$movie->getYear());
		$success = $stm->execute();
		if ($success) {
            $movie->setId($this->pdo->lastInsertId());
        }
		    return $success;
	}

	public function delete($id) {
	    $sql = 'DELETE FROM `films` WHERE `id`=:id';
	    $stm = $this->pdo->prepare($sql);
	    $stm->bindparam(":id", $id);
	    $stm->execute();
	    return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteDirector($id) {
        $sql = 'DELETE FROM `director` WHERE `id`=:id';
        $stm = $this->pdo->prepare($sql);
        $stm->bindparam(":id", $id);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
}
