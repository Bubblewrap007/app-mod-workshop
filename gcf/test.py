#!/usr/bin/env python

"""Unit tests for the Cloud Function."""

import os
import unittest
from unittest.mock import patch, MagicMock

os.environ.setdefault("PROJECT_ID", "awesome-project-489421")
os.environ.setdefault("DB_HOST", "35.192.15.69")
os.environ.setdefault("DB_NAME", "image_catalog")
os.environ.setdefault("DB_USER", "appmod-phpapp-user")
os.environ.setdefault("DB_PASS", "Cloudedminday3@")


class TestGeminiDescribe(unittest.TestCase):
    @patch("main.GenerativeModel")
    @patch("main.vertexai.init")
    def test_gemini_describe_image(self, mock_init, mock_model_class):
        from main import gemini_describe_image_from_gcs
        mock_model = MagicMock()
        mock_model.generate_content.return_value.text = "A beautiful sunset over the ocean."
        mock_model_class.return_value = mock_model

        result = gemini_describe_image_from_gcs("gs://bucket/image.png", "image/png")
        self.assertEqual(result, "A beautiful sunset over the ocean.")


class TestUpdateDB(unittest.TestCase):
    @patch("main.pymysql.connect")
    def test_update_db(self, mock_connect):
        from main import update_db_with_description
        mock_conn = MagicMock()
        mock_connect.return_value.__enter__ = MagicMock(return_value=mock_conn)
        mock_connect.return_value.__exit__ = MagicMock(return_value=False)

        update_db_with_description(
            "uploads/image.png", "A test caption",
            "user", "pass", "host", "db"
        )
        mock_connect.assert_called_once()


if __name__ == "__main__":
    unittest.main()
