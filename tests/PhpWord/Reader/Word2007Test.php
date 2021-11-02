<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader;

use DateTime;
use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\Word2007
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test canRead() method
     */
    public function testCanRead()
    {
        $object = new Word2007();
        $filename = __DIR__ . '/../_files/documents/reader.docx';
        $this->assertTrue($object->canRead($filename));
    }

    /**
     * Can read exception
     */
    public function testCanReadFailed()
    {
        $object = new Word2007();
        $filename = __DIR__ . '/../_files/documents/foo.docx';
        $this->assertFalse($object->canRead($filename));
    }

    /**
     * Load
     */
    public function testLoad()
    {
        $filename = __DIR__ . '/../_files/documents/reader.docx';
        $phpWord = IOFactory::load($filename);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $phpWord);
        $this->assertTrue($phpWord->getSettings()->hasDoNotTrackMoves());
        $this->assertFalse($phpWord->getSettings()->hasDoNotTrackFormatting());
        $this->assertEquals(100, $phpWord->getSettings()->getZoom());

        $doc = TestHelperDOCX::getDocument($phpWord);
        $this->assertEquals('0', $doc->getElementAttribute('/w:document/w:body/w:p/w:r[w:t/node()="italics"]/w:rPr/w:b', 'w:val'));
    }

    /**
     * Load a Word 2011 file
     */
    public function testLoadWord2011()
    {
        $filename = __DIR__ . '/../_files/documents/reader-2011.docx';
        $phpWord = IOFactory::load($filename);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $phpWord);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p[3]/w:r/w:pict/v:shape/v:imagedata'));
    }

    public function testLoadComments()
    {
        $filename = __DIR__ . '/../_files/documents/reader-ooxml-comments.docx';
        $phpWord = IOFactory::load($filename);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $phpWord);

        //$doc = TestHelperDOCX::getDocument($phpWord);
        $comment = new Comment('shaedrich', new DateTime('2021-10-28T13:56:00Z'), 'SH');
        $comment2 = $phpWord->getComments()[0];
        $this->assertEquals($comment->getAuthor(), $comment2->getAuthor());
        $this->assertEquals($comment->getInitials(), $comment2->getInitials());
    }
}
