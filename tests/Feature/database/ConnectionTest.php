<?php

it('connects to the database successfully', function () {
    expect($this->pdo)->toBeInstanceOf(PDO::class);
});
