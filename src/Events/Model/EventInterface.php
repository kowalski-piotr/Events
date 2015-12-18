<?php

/* 
 * The MIT License
 *
 * Copyright 2015 pchel.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Events\Model;

 use Events\Model\PostInterface;

 interface EventInterface
 {
     /**
      * Should return a set of all blog posts that we can iterate over. Single entries of the array are supposed to be
      * implementing \Blog\Model\PostInterface
      *
      * @return array|PostInterface[]
      */
     public function findAllPosts();

     /**
      * Should return a single blog post
      *
      * @param  int $id Identifier of the Post that should be returned
      * @return PostInterface
      */
     public function findPost($id);

     /**
      * Should save a given implementation of the PostInterface and return it. If it is an existing Post the Post
      * should be updated, if it's a new Post it should be created.
      *
      * @param  PostInterface $blog
      * @return PostInterface
      */
     public function savePost($blog);
 }